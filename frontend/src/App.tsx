import React from 'react';
import './App.css';
import MortgageCalculator from './components/MortgageCalculator';

function App() {
  return (
    <div className="App">
      <header className="app-header">
        <div className="header-content">
          <div className="brand">
            <h1>UAE Mortgage Calculator</h1>
            <p className="tagline">Professional loan analysis and amortization planning</p>
          </div>
          <div className="header-badge">
            <span className="tech-stack">Laravel 11 + React.js</span>
          </div>
        </div>
      </header>
      
      <main className="main-content">
        <MortgageCalculator />
      </main>
      
      <footer className="app-footer">
        <div className="footer-content">
          <p>&copy; 2025 UAE Mortgage Calculator - Enterprise Financial Planning Tool</p>
          <div className="footer-tech">
            <span>Powered by Laravel 11 API & React.js</span>
          </div>
        </div>
      </footer>
    </div>
  );
}

export default App;
