// src/Header.jsx (atau bagian Navbar di App.jsx)
import React from 'react';
import logoGelap from './assets/logoGelap.png';

const Header = () => {
  return (
    <nav className="navbar">
      <div className="logo-container">
        <img src={logoGelap} alt="Logo Chloe" className="logo-img" />
        <div className="logo-text-group">
          <span>Chloe</span>
          <small>Campus Venue & Facilities Online E-Booking</small>
        </div>
      </div>

      <div className="nav-links">
        <a href="#">Dashboard</a>
        <a href="#">Booking</a>
        <a href="#">Reports</a>
        <button>Sign In</button>
      </div>
    </nav>
  );
};

export default Header;