import { LoanData, LoanCalculationResponse } from '../types/loan';

const API_BASE_URL = 'http://localhost:8080/api';

export const loanService = {
  async calculateLoan(loanData: LoanData): Promise<LoanCalculationResponse> {
    const response = await fetch(`${API_BASE_URL}/loans/calculate`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(loanData),
    });

    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(errorData.error || 'Failed to calculate loan');
    }

    return response.json();
  },
};