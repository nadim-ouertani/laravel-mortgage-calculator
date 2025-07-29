import React from 'react';
import './LoadingSpinner.css';

const LoadingSpinner: React.FC = () => {
  return (
    <div className="loading">
      <div className="spinner"></div>
      <p>Calculating your loan...</p>
    </div>
  );
};

export default LoadingSpinner;