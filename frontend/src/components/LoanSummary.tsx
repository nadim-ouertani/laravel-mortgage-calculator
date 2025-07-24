import React from 'react';
import { LoanDetails, Schedule } from '../types/loan';
import { formatCurrency, formatPercent } from '../utils/formatters';
import './LoanSummary.css';

interface LoanSummaryProps {
  loanDetails: LoanDetails;
  standardSchedule: Schedule;
  extraSchedule?: Schedule;
}

const LoanSummary: React.FC<LoanSummaryProps> = ({ 
  loanDetails, 
  standardSchedule, 
  extraSchedule 
}) => {
  return (
    <div className="loan-summary">
      <div className="summary-grid">
        <div className="summary-item">
          <div className="summary-label">Loan Amount</div>
          <div className="summary-value">{formatCurrency(loanDetails.loan_amount)}</div>
        </div>
        
        <div className="summary-item">
          <div className="summary-label">Interest Rate</div>
          <div className="summary-value">{formatPercent(loanDetails.annual_interest_rate)}</div>
        </div>
        
        <div className="summary-item">
          <div className="summary-label">Loan Term</div>
          <div className="summary-value">{loanDetails.loan_term_years} years</div>
        </div>
        
        <div className="summary-item">
          <div className="summary-label">Monthly Payment</div>
          <div className="summary-value">{formatCurrency(loanDetails.calculated_monthly_payment)}</div>
        </div>

        {loanDetails.monthly_extra_payment > 0 && (
          <>
            <div className="summary-item">
              <div className="summary-label">Extra Payment</div>
              <div className="summary-value">{formatCurrency(loanDetails.monthly_extra_payment)}</div>
            </div>
            
            {loanDetails.effective_interest_rate && (
              <div className="summary-item">
                <div className="summary-label">Effective Interest Rate</div>
                <div className="summary-value effective-rate">
                  {formatPercent(loanDetails.effective_interest_rate)}
                </div>
              </div>
            )}
          </>
        )}
      </div>
    </div>
  );
};

export default LoanSummary;