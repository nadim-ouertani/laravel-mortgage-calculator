import React from 'react';
import './App.css';
import MortgageCalculator from './components/MortgageCalculator';

function App() {
  return (
    <div className="App">
      <header className="app-header">
        <h1>Mortgage Loan Calculator</h1>
        <p>Calculate your monthly payments and see how extra payments can save you money</p>
      </header>
      
      <main>
        <MortgageCalculator />
      </main>
      
      <footer className="app-footer">
        <p>&copy; 2025 Mortgage Calculator. Built with Laravel 12 API and React.js.</p>
      </footer>
    </div>
  );
}

export default App;
