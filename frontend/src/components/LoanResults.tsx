import React, { useState } from 'react';
import { LoanCalculationResponse } from '../types/loan';
import LoanSummary from './LoanSummary';
import ScheduleTable from './ScheduleTable';
import ExtraPaymentBenefits from './ExtraPaymentBenefits';
import './LoanResults.css';

interface LoanResultsProps {
  data: LoanCalculationResponse;
}

const LoanResults: React.FC<LoanResultsProps> = ({ data }) => {
  const [activeTab, setActiveTab] = useState<'standard' | 'extra'>('standard');

  const { loan_details, standard_schedule, extra_payment_schedule } = data;

  return (
    <div className="results">
      <div className="calculator-section">
        <h2>Loan Summary</h2>
        <LoanSummary 
          loanDetails={loan_details}
          standardSchedule={standard_schedule}
          extraSchedule={extra_payment_schedule}
        />

        <div className="schedule-tabs">
          <button 
            className={`tab ${activeTab === 'standard' ? 'active' : ''}`}
            onClick={() => setActiveTab('standard')}
          >
            Standard Schedule
          </button>
          {extra_payment_schedule && (
            <button 
              className={`tab ${activeTab === 'extra' ? 'active' : ''}`}
              onClick={() => setActiveTab('extra')}
            >
              With Extra Payments
            </button>
          )}
        </div>

        {activeTab === 'standard' ? (
          <div className="schedule-content">
            <div className="schedule-header">
              <h3>Standard Amortization Schedule</h3>
              <div className="schedule-summary">
                <div className="summary-grid">
                  <div className="summary-item">
                    <div className="summary-label">Total Payments</div>
                    <div className="summary-value">{standard_schedule.total_payments.toLocaleString()} months</div>
                  </div>
                  <div className="summary-item">
                    <div className="summary-label">Total Interest</div>
                    <div className="summary-value">{formatCurrency(standard_schedule.total_interest)}</div>
                  </div>
                  <div className="summary-item">
                    <div className="summary-label">Total Amount Paid</div>
                    <div className="summary-value">{formatCurrency(standard_schedule.total_interest + standard_schedule.total_principal)}</div>
                  </div>
                </div>
              </div>
            </div>
            <ScheduleTable schedule={standard_schedule} showExtraPayment={false} />
          </div>
        ) : extra_payment_schedule && (
          <div className="schedule-content">
            <div className="schedule-header">
              <h3>Schedule with Extra Payments</h3>
              <ExtraPaymentBenefits 
                standardSchedule={standard_schedule}
                extraSchedule={extra_payment_schedule}
              />
            </div>
            <ScheduleTable schedule={extra_payment_schedule} showExtraPayment={true} />
          </div>
        )}
      </div>
    </div>
  );
};

const formatCurrency = (amount: number): string => {
  const formatted = new Intl.NumberFormat('en-AE', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(amount);
  
  return `${formatted} دإ`;
};

export default LoanResults;