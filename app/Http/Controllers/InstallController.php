<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InstallController extends Controller
{
    public function step1()
    {
        $requirements = [
            'PHP Version (>= 8.2)' => version_compare(PHP_VERSION, '8.2.0', '>='),
            'PDO Extension' => extension_loaded('pdo'),
            'Mbstring Extension' => extension_loaded('mbstring'),
            'OpenSSL Extension' => extension_loaded('openssl'),
            'Ctype Extension' => extension_loaded('ctype'),
            'JSON Extension' => extension_loaded('json'),
            'XML Extension' => extension_loaded('xml'),
            'cURL Extension' => extension_loaded('curl'),
            'Storage Directory Writable' => is_writable(storage_path()),
            'Bootstrap/Cache Directory Writable' => is_writable(base_path('bootstrap/cache')),
        ];

        $allPassed = !in_array(false, $requirements, true);

        return view('installer.step1', compact('requirements', 'allPassed'));
    }

    public function step2()
    {
        return view('installer.step2');
    }

    public function saveStep2(Request $request)
    {
        $request->validate([
            'db_connection' => 'required|in:sqlite,mysql',
            'db_host' => 'required_if:db_connection,mysql',
            'db_port' => 'required_if:db_connection,mysql',
            'db_database' => 'required',
            'db_username' => 'nullable',
            'db_password' => 'nullable',
        ]);

        $connection = $request->db_connection;
        $host = $request->db_host;
        $port = $request->db_port;
        $database = $request->db_database;
        $username = $request->db_username;
        $password = $request->db_password;

        if ($connection === 'mysql') {
            try {
                $testDb = new \PDO("mysql:host={$host};port={$port};dbname={$database}", $username, $password, [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                ]);
            } catch (\Exception $e) {
                return back()->with('error', 'Database connection failed: ' . $e->getMessage())->withInput();
            }
        }

        // Update .env file
        $this->updateEnv([
            'DB_CONNECTION' => $connection,
            'DB_HOST' => $host ?? '127.0.0.1',
            'DB_PORT' => $port ?? '3306',
            'DB_DATABASE' => $database,
            'DB_USERNAME' => $username ?? '',
            'DB_PASSWORD' => $password ?? '',
        ]);

        return redirect()->route('install.step3');
    }

    public function step3()
    {
        return view('installer.step3');
    }

    public function runMigration(Request $request)
    {
        try {
            Artisan::call('migrate:fresh', ['--force' => true, '--seed' => true]);
            return redirect()->route('install.step4')->with('success', 'Database tables created and demo data seeded successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Migration failed: ' . $e->getMessage());
        }
    }

    public function step4()
    {
        return view('installer.step4');
    }

    public function saveStep4(Request $request)
    {
        $request->validate([
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255',
            'admin_password' => 'required|string|min:6|confirmed',
        ]);

        User::updateOrCreate(
            ['email' => $request->admin_email],
            [
                'name' => $request->admin_name,
                'password' => Hash::make($request->admin_password),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        return redirect()->route('install.step5')->with('success', 'Administrator account configured.');
    }

    public function step5()
    {
        return view('installer.step5');
    }

    public function saveStep5(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'currency' => 'required|string|max:10',
            'tax' => 'required|numeric|min:0',
            'invoice_prefix' => 'required|string|max:10',
        ]);

        Setting::setByKey('store_name', $request->store_name);
        Setting::setByKey('currency', $request->currency);
        Setting::setByKey('tax', $request->tax);
        Setting::setByKey('invoice_prefix', $request->invoice_prefix);

        return redirect()->route('install.step6');
    }

    public function step6()
    {
        // Mark as installed
        file_put_contents(storage_path('installed'), date('Y-m-d H:i:s'));

        return view('installer.step6');
    }

    private function updateEnv(array $data)
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            copy(base_path('.env.example'), $envPath);
        }

        $envContent = file_get_contents($envPath);
        foreach ($data as $key => $value) {
            if (preg_match("/^{$key}=.*/m", $envContent)) {
                $envContent = preg_replace("/^{$key}=.*/m", "{$key}=\"{$value}\"", $envContent);
            } else {
                $envContent .= "\n{$key}=\"{$value}\"";
            }
        }
        file_put_contents($envPath, $envContent);
    }
}
