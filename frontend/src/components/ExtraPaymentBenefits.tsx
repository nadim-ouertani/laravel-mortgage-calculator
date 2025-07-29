import React from 'react';
import { Schedule } from '../types/loan';
import { formatCurrency, formatNumber } from '../utils/formatters';
import './ExtraPaymentBenefits.css';

interface ExtraPaymentBenefitsProps {
  standardSchedule: Schedule;
  extraSchedule: Schedule;
}

const ExtraPaymentBenefits: React.FC<ExtraPaymentBenefitsProps> = ({ 
  standardSchedule, 
  extraSchedule 
}) => {
  const interestSavings = standardSchedule.total_interest - extraSchedule.total_interest;
  const timeSavings = standardSchedule.total_payments - extraSchedule.total_payments;
  const totalExtraPaid = extraSchedule.total_extra_payments || 0;

  return (
    <div className="extra-savings">
      <h3>💰 Benefits of Extra Payments</h3>
      
      <div className="summary-grid">
        <div className="summary-item">
          <div className="summary-label">Interest Savings</div>
          <div className="summary-value savings-highlight">
            {formatCurrency(interestSavings)}
          </div>
        </div>
        
        <div className="summary-item">
          <div className="summary-label">Time Savings</div>
          <div className="summary-value savings-highlight">
            {formatNumber(timeSavings)} months
          </div>
        </div>
        
        <div className="summary-item">
          <div className="summary-label">Total Extra Paid</div>
          <div className="summary-value">
            {formatCurrency(totalExtraPaid)}
          </div>
        </div>
        
        <div className="summary-item">
          <div className="summary-label">New Payoff Time</div>
          <div className="summary-value">
            {formatNumber(extraSchedule.total_payments)} months
          </div>
        </div>
      </div>
      
      <div className="benefits-summary">
        <strong>Summary:</strong> By paying an extra {formatCurrency(totalExtraPaid)} over the life of the loan, 
        you'll save {formatCurrency(interestSavings)} in interest and pay off your loan {timeSavings} months earlier!
      </div>
    </div>
  );
};

export default ExtraPaymentBenefits;