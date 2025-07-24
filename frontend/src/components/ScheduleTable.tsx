import React from 'react';
import { Schedule } from '../types/loan';
import { formatCurrency, formatNumber } from '../utils/formatters';
import './ScheduleTable.css';

interface ScheduleTableProps {
  schedule: Schedule;
  showExtraPayment: boolean;
}

const ScheduleTable: React.FC<ScheduleTableProps> = ({ schedule, showExtraPayment }) => {
  return (
    <div className="table-container">
      <table className="schedule-table">
        <thead>
          <tr>
            <th>Month</th>
            <th>Starting Balance</th>
            <th>Payment</th>
            <th>Principal</th>
            <th>Interest</th>
            {showExtraPayment && <th>Extra Payment</th>}
            <th>Ending Balance</th>
            {showExtraPayment && <th>Remaining Term</th>}
          </tr>
        </thead>
        <tbody>
          {schedule.entries.map((entry) => (
            <tr key={entry.month}>
              <td>{formatNumber(entry.month)}</td>
              <td>{formatCurrency(entry.starting_balance)}</td>
              <td>{formatCurrency(entry.monthly_payment)}</td>
              <td>{formatCurrency(entry.principal)}</td>
              <td>{formatCurrency(entry.interest)}</td>
              {showExtraPayment && (
                <td>{formatCurrency(entry.extra_payment || 0)}</td>
              )}
              <td>{formatCurrency(entry.ending_balance)}</td>
              {showExtraPayment && (
                <td>{formatNumber(entry.remaining_term || 0)}</td>
              )}
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default ScheduleTable;