<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Seller;
use App\Models\Customer;
use App\Models\Barang;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Orders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;



class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'role' => 'required|in:seller,customer',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'role.required' => 'Pilih role terlebih dahulu.',
            'role.in' => 'Role harus seller atau customer.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        // Cek apakah email sudah digunakan di role yang sama
        if ($request->role === 'seller' && Seller::where('email', $request->email)->exists()) {
            return back()->withErrors(['email' => 'Email sudah digunakan untuk seller.'])->withInput();
        }
        if ($request->role === 'customer' && Customer::where('email', $request->email)->exists()) {
            return back()->withErrors(['email' => 'Email sudah digunakan untuk customer.'])->withInput();
        }

        // Simpan ke tabel sesuai role
        if ($request->role === 'seller') {
            Seller::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
        } else {
            Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
        }

        return back()->with('success', 'Registrasi berhasil. Silakan login.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:seller,customer',
        ]);

        if ($request->role === 'seller') {
            $user = Seller::where('email', $request->email)->first();
        } else {
            $user = Customer::where('email', $request->email)->first();
        }

        if ($user && Hash::check($request->password, $user->password)) {
            $request->session()->put('user_id', $user->id);
            $request->session()->put('role', $request->role);
            $request->session()->put('name', $user->name);

            if ($request->role === 'seller') {
                return redirect()->route('dashboard.sellers.etalase');
            } else {
                return redirect()->route('dashboard.customers.home');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }

    public function dashboardSellerHome(Request $request)
    {
        $userId = $request->session()->get('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first');
        }
        $products = Barang::where('seller_id', $userId)->get();
        $saldo = Seller::where('id', $userId)->first()->saldo;

        return view('dashboard.sellers.etalase', compact('products'), compact('saldo'));
    }

    public function dashboardSellerData(Request $request)
    {
        $userId = $request->session()->get('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $products = Barang::where('seller_id', $userId)
            ->select('id', 'name', 'description', 'price', 'stock', 'image')
            ->get();

        $saldo = Seller::where('id', $userId)->first()->saldo;

        return view('dashboard.sellers.data', compact('products'), compact('saldo'));
    }

    public function dashboardCustomerHome(Request $request)
    {

        $userId = $request->session()->get('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $products = Barang::all();
        return view('dashboard.customers.home', compact('products'));
    }

    public function dashboardCustomerRiwayat(Request $request)
    {
        $userId = $request->session()->get('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        // join orders dengan barang
        $products = Orders::where('orders.customer_id', $userId)
            ->join('barang', 'orders.barang_id', '=', 'barang.id')
            ->select(
                'orders.id',
                'barang.name as nama_produk',
                'barang.price as harga',
                'orders.jumlah',
                DB::raw('barang.price * orders.jumlah as total'),
                'orders.created_at',
                'orders.status',
                'orders.barang_id'
            )
            ->get();


        return view('dashboard.customers.riwayat', compact('products'));
    }

    public function dashboardSellerRiwayat(Request $request)
    {
        $userId = $request->session()->get('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        // join orders dengan barang
        $products = Barang::where('barang.seller_id', $userId)
            ->join('orders', 'orders.barang_id', '=', 'barang.id')
            ->select(
                'orders.id',
                'barang.name as nama_produk',
                'barang.price as harga',
                'orders.jumlah',
                DB::raw('barang.price * orders.jumlah as total'),
                DB::raw('barang.price * orders.jumlah * 90/100 as komisi'),
                'orders.created_at',
                'orders.status'
            )
            ->get();

        $saldo = Seller::where('id', $userId)->first()->saldo;


        return view('dashboard.sellers.riwayat', compact('products'), compact('saldo'));
    }

    public function AddProduct(Request $request)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'desc'   => 'required|string',
            'harga'  => 'required|numeric',
            'jumlah' => 'required|integer',
            'image'  => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // Ambil file
        $imageFile = $request->file('image');

        // Buat nama file unik
        $uniqueName = uniqid() . '.' . $imageFile->getClientOriginalExtension();

        // Pastikan folder tujuan ada
        $destinationPath = base_path('database/images');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        // Pindahkan file ke folder `public/database/image`
        $imageFile->move($destinationPath, $uniqueName);
        $userId = $request->session()->get('user_id');

        // Simpan ke database (hanya nama file)
        Barang::create([
            'seller_id'   => $userId,
            'name'        => $request->nama,
            'description' => $request->desc,
            'price'       => $request->harga,
            'stock'       => $request->jumlah,
            'image'       => $uniqueName
        ]);


        return response()->json(['success' => true, 'message' => 'Produk berhasil ditambahkan']);
    }

    public function DetailProduct($id)
    {
        $detail = Barang::where('id', $id)
            ->select('name', 'description', 'price', 'stock', 'image')
            ->first();

        return response()->json($detail);
    }

    public function DeleteProduct($id)
    {
        $detail = Barang::where('id', $id)->delete();

        return response()->json($detail);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        // Auth::user()->name;
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function UpdateProduct(Request $request, $id)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'desc'   => 'required|string',
            'harga'  => 'required|numeric',
            'jumlah' => 'required|integer',
            'image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $product = Barang::findOrFail($id);

        // Simpan nama gambar lama
        $oldImage = $product->image;

        // Cek apakah user upload gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama
            $oldPath = base_path('database/images/' . $oldImage);
            if (file_exists($oldPath) && is_file($oldPath)) {
                unlink($oldPath);
            }

            // Upload gambar baru
            $imageFile = $request->file('image');
            $uniqueName = uniqid() . '.' . $imageFile->getClientOriginalExtension();
            $destinationPath = base_path('database/images');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $imageFile->move($destinationPath, $uniqueName);
            $product->image = $uniqueName;
        }

        // Update data lainnya
        $product->name        = $request->nama;
        $product->description = $request->desc;
        $product->price       = $request->harga;
        $product->stock       = $request->jumlah;
        $product->save();

        return response()->json(['success' => true, 'message' => 'Produk berhasil diupdate']);
    }

    public function AddTransaksi(Request $request)
    {
        $request->validate([
            'customer_id'   => 'required|integer',
            'barang_id'   => 'required|integer',
            'status'  => 'required|integer',
            'jumlah' => 'required|integer',
        ]);
        Orders::create([
            'customer_id'        => $request->customer_id,
            'barang_id' => $request->barang_id,
            'status'       => $request->status,
            'jumlah'       => $request->jumlah,
        ]);

        Barang::where('id', $request->barang_id)->decrement('stock', $request->jumlah);
        return response()->json(['success' => true, 'message' => 'Produk berhasil ditambahkan']);
    }

    public function getHarga(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Validate input
        $request->validate([
            'total_price' => 'required|numeric|min:1'
        ]);

        $totalPrice = $request->input('total_price');

        // Generate unique order ID with timestamp
        $orderId = 'ORDER-' . time() . '-' . rand(1000, 9999);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $totalPrice,
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        return response()->json([
            'snapToken' => $snapToken,
            'order_id' => $orderId
        ]);
    }

    public function UpdateStatusCustomer($id, $total, $barang_id)
    {

        // Update status order
        $product = Orders::where('id', $id)->update([
            'status' => 3
        ]);
        // Kalkulasi komisi - seller mendapat 90% dari total
        $commissionRate = 0.90;
        $sellerPayment = $total * $commissionRate;

        $barang = Barang::where('id', $barang_id)->first();

        $seller = Seller::where('id', $barang->seller_id)->first();

        $seller->update(['saldo' => $seller->saldo + $sellerPayment]);
        if ($product) {
            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diupdate'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    public function UpdateStatusSeller($id)
    {

        $product = Orders::where('id', $id)->update([
            'status' => 2
        ]);

        if ($product) {
            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diupdate'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
}
