<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use App\Helpers\Helper;

class Google2FAController extends Controller
{
    
    public function show()
    {
        $google2fa = new Google2FA();
        $user = auth()->user();

        if(empty(Helper::checkGoogle2FaIsenabled()))
        {
            return Helper::authenticated($user);
        }
        
        // If 2FA is enabled and already verified, redirect to dashboard
        if ($user->google2fa_enabled && session()->has('2fa_verified')) {
            return Helper::authenticated($user);
        }

        // Ensure the user has a Google 2FA secret
        if (!$user->google2fa_secret) {
            $user->generateGoogle2FASecret();
        }

        // Generate the OTP URL
        $google2fa_url = $google2fa->getQRCodeUrl(
            config('app.name'), // App name
            $user->email,       // User's email
            $user->google2fa_secret
        );

        // Create the QR code from the OTP URL
        $qrCode = new QrCode($google2fa_url);

        // Initialize the PngWriter to generate the PNG image
        $writer = new PngWriter();

        // Define the file path where the QR code image will be saved
        $filePath = storage_path('app/public/google2fa/' . $user->id . '-qrcode.png');

        // Create the directory if it doesn't exist
        if (!file_exists(storage_path('app/public/google2fa'))) {
            mkdir(storage_path('app/public/google2fa'), 0755, true);
        }

        // Save the QR code image using the `write()` method, and then manually save it
        $pngData = $writer->write($qrCode)->getString();  // Get the QR code as a string (image data)

        // Save the image data to the file path
        file_put_contents($filePath, $pngData);  // Save the QR code image to the file

        // Return the path to display the QR code in your view
        return view('google2fa.setup', ['qrCodePath' => '/storage/google2fa/' . $user->id . '-qrcode.png']);
    }


    public function enable(Request $request)
    {
        $request->validate([
            'google2fa_token' => 'required|digits:6' 
        ]);

        $google2fa = new Google2FA();
        $user = auth()->user();

        // Verify the token
        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->google2fa_token);

        if ($valid) {
            
            session(['2fa_verified' => true]);

            //return redirect()->route('home')->with('status', '2FA enabled!');
            return Helper::authenticated($user);
        } else {
            return redirect()->back()->withErrors(['google2fa_token' => 'The provided token is invalid.']);
        }
    }
}
