import React from 'react';
import './App.css';
import MortgageCalculator from './components/MortgageCalculator';

function App() {
  return (
    <div className="App">
      <header className="app-header">
        <div className="header-content">
          <div className="brand">
            <h1>Mortgage Calculator</h1>
            <p className="tagline">PHP LARAVEL PRACTICAL EXAM</p>
          </div>
          <div className="header-badge">
            <span className="tech-stack">Laravel 12 + React.js</span>
          </div>
        </div>
      </header>
      
      <main className="main-content">
        <MortgageCalculator />
      </main>
      
      <footer className="app-footer">
        <div className="footer-content">
          <p>&copy; 2025 Mortgage Calculator</p>
          <div className="footer-tech">
            <span>Powered by Laravel 12 API & React.js</span>
          </div>
        </div>
      </footer>
    </div>
  );
}

export default App;
