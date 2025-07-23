<?php

namespace App\Http\Controllers\api;

use App\Models\Payment;
use App\Models\JobApplication;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function payStudent(Request $request)
    {
        $request->validate([
            'application_id' => 'required|exists:job_applications,id',
            'amount' => 'required|numeric|min:1',
            'payment_method_id' => 'required|string', 
        ]);

        $application = JobApplication::with('student', 'jobPost')->findOrFail($request->application_id);

        if ($application->jobPost->company_id !== Auth::id()) {
            return response()->json(['error' => 'not allowed to pay for this job'], 403);
        }

        // تأكد أن الطالب عنده حساب Stripe Connect
        // if (!$application->student->stripe_account_id) {
        //     return response()->json(['error' => 'student not have stripe account'], 400);
        // }

        Stripe::setApiKey(env('STRIPE_SECRET'));
        try {
            $paymentIntentData = [
                'amount' => $request->amount * 100, // بالمليم
                'currency' => 'EGP',
                'payment_method' => $request->payment_method_id,
                'confirm' => true,
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never',
                ],
                'metadata' => [
                    'application_id' => $application->id,
                    'job_post_id' => $application->jobPost->id,
                ],
            ];


            if ($application->student->stripe_account_id) {
                $paymentIntentData['transfer_data'] = [
                    'destination' => $application->student->stripe_account_id,
                ];
            }

            $paymentIntent = PaymentIntent::create($paymentIntentData);

            Payment::create([
                'company_id' => Auth::id(),
                'student_id' => $application->student_id,
                'job_application_id' => $application->id,
                'amount' => $request->amount,
                'currency' => 'EGP',
                'payment_intent_id' => $paymentIntent->id,
                'status' => 'succeeded',
            ]);
            $application->jobPost->status = 'paid';
            $application->jobPost->save();

            return response()->json(['message' => 'payment success']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'payment failed: ' . $e->getMessage()], 500);
        }
    }
}
