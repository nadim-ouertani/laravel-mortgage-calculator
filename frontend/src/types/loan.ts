export interface LoanData {
  loan_amount: number;
  annual_interest_rate: number;
  loan_term_years: number;
  monthly_extra_payment?: number;
}

export interface LoanDetails {
  id?: number;
  loan_amount: number;
  annual_interest_rate: number;
  loan_term_years: number;
  monthly_extra_payment: number;
  calculated_monthly_payment: number;
  effective_interest_rate?: number;
}

export interface ScheduleEntry {
  month: number;
  starting_balance: number;
  monthly_payment: number;
  principal: number;
  interest: number;
  ending_balance: number;
  extra_payment?: number;
  remaining_term?: number;
}

export interface Schedule {
  total_payments: number;
  total_interest: number;
  total_principal: number;
  total_extra_payments?: number;
  interest_savings?: number;
  time_savings?: number;
  entries: ScheduleEntry[];
}

export interface LoanCalculationResponse {
  success: boolean;
  loan_id: number;
  loan_details: LoanDetails;
  standard_schedule: Schedule;
  extra_payment_schedule?: Schedule;
}

export interface ValidationErrors {
  [key: string]: string[];
}