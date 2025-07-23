<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoanCalculationRequest;
use App\Application\Services\LoanApplicationService;
use App\Http\Transformers\LoanTransformer;
use Illuminate\Http\JsonResponse;

class LoanController extends Controller
{
    public function __construct(
        private LoanApplicationService $loanService,
        private LoanTransformer $transformer
    ) {}

    public function calculateLoan(LoanCalculationRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $result = $this->loanService->createLoanCalculation($data);
            
            $response = $this->transformer->transformLoanCalculationResponse(
                $result['loan'], 
                $result['standard_schedule'], 
                $result['extra_payment_schedule']
            );
            
            return response()->json($response);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => ['calculation' => [$e->getMessage()]]
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request',
                'errors' => ['general' => ['Unable to calculate loan payment']]
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $result = $this->loanService->getLoanWithSchedules($id);
            $response = $this->transformer->transformLoanDetailsResponse(
                $result['loan'], 
                $result['standard_schedule'], 
                $result['extra_payment_schedule']
            );

            return response()->json($response);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Loan not found',
                'errors' => ['loan_id' => ['The specified loan does not exist']]
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the loan',
                'errors' => ['general' => ['Unable to retrieve loan details']]
            ], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->loanService->deleteLoan($id);
        
        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Loan deleted successfully'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Loan not found',
            'errors' => ['loan_id' => ['The specified loan does not exist']]
        ], 404);
    }
}
