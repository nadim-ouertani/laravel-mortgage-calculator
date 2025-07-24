export const formatCurrency = (amount: number): string => {
  const formatted = new Intl.NumberFormat('en-AE', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(amount);
  
  // Currency symbol formatting
  return `${formatted} دإ`;
};

export const formatNumber = (number: number): string => {
  return new Intl.NumberFormat('en-US').format(number);
};

export const formatPercent = (percent: number): string => {
  return `${parseFloat(percent.toString()).toFixed(3)}%`;
};