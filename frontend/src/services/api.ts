import { LoanData, LoanCalculationResponse, ValidationErrors } from '../types/loan';

const API_BASE_URL = process.env.REACT_APP_API_URL || 'http://localhost:8080/api';

export class ApiError extends Error {
  public status: number;
  public validationErrors?: ValidationErrors;

  constructor(message: string, status: number, validationErrors?: ValidationErrors) {
    super(message);
    this.name = 'ApiError';
    this.status = status;
    this.validationErrors = validationErrors;
  }
}

export const calculateLoan = async (loanData: LoanData): Promise<LoanCalculationResponse> => {
  const requestData = {
    ...loanData,
    monthly_extra_payment: loanData.monthly_extra_payment || 0
  };

  try {
    const response = await fetch(`${API_BASE_URL}/loans/calculate`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(requestData)
    });

    const result = await response.json();

    if (!response.ok) {
      if (response.status === 422 && result.errors) {
        throw new ApiError('Validation failed', response.status, result.errors);
      }
      throw new ApiError(
        result.error || `HTTP ${response.status}: ${response.statusText}`,
        response.status
      );
    }

    if (!result.success) {
      throw new ApiError(result.error || 'Unknown error occurred', response.status);
    }

    return result;
  } catch (error) {
    if (error instanceof ApiError) {
      throw error;
    }
    
    if (error instanceof TypeError && error.message.includes('fetch')) {
      throw new ApiError(
        'Unable to connect to the server. Please make sure the Laravel API is running on port 8080.',
        0
      );
    }
    
    throw new ApiError('An unexpected error occurred', 0);
  }
};