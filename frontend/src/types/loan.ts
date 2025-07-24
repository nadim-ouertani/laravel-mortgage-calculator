export interface LoanData {
  loan_amount: number;
  annual_interest_rate: number;
  loan_term_years: number;
  monthly_extra_payment?: number;
}

export interface LoanResult {
  loan_details: {
    loan_amount: number;
    annual_interest_rate: number;
    loan_term_years: number;
    monthly_extra_payment: number;
    calculated_monthly_payment: number;
    effective_interest_rate: number;
  };
  amortization_schedule: ScheduleEntry[];
}

export interface ScheduleEntry {
  month_number: number;
  starting_balance: number;
  monthly_payment: number;
  principal_component: number;
  interest_component: number;
  ending_balance: number;
}