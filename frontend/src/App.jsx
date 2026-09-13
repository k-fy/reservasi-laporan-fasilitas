import { useEffect, useState } from 'react'
import './App.css'

function App() {
  const [facilities, setFacilities] = useState([])
  const [searchFacility, setSearchFacility] = useState('')
  const [searchResult, setSearchResult] = useState([])

  // Mengambil data fasilitas dari Laravel API
  useEffect(() => {
    fetch('http://127.0.0.1:8000/api/facilities')
      .then((response) => response.json())
      .then((data) => {
        setFacilities(data)
      })
      .catch((error) => {
        console.error('Error:', error)
      })
  }, [])

  // Fungsi untuk mencari fasilitas
  const handleCheck = () => {
    const keyword = searchFacility.toLowerCase().trim()

    if (keyword === '') {
      setSearchResult([])
      return
    }

    const result = facilities.filter((facility) =>
      facility.name.toLowerCase().includes(keyword) ||
      facility.type.toLowerCase().includes(keyword) ||
      facility.location.toLowerCase().includes(keyword)
    )

    setSearchResult(result)
  }

  return (
    <div className="app">

      {/* NAVBAR */}
      <nav className="navbar">
        <div className="logo">
          <span>Chloe</span>
          <small>Campus Venue & Facilities Online E-Booking</small>
        </div>

        <div className="nav-links">
          <a href="#">Dashboard</a>
          <a href="#">Booking</a>
          <a href="#">Reports</a>
          <button>Sign In</button>
        </div>
      </nav>


      {/* HERO */}
      <section className="hero">

        <div className="hero-overlay"></div>

        <div className="hero-content">
          <p className="welcome">Welcome to Chloe.</p>

          <h1>
            Looking for your
            <br />
            <span>Perfect</span> Venue?
          </h1>

          <div className="hero-buttons">
            <button className="book-btn">
              Book Now
            </button>

            <button className="learn-btn">
              Learn more →
            </button>
          </div>
        </div>


        {/* AVAILABILITY CHECK */}
        <div className="availability-card">

          <h2>Availability Check</h2>

          <div className="line"></div>

          <label>Search Facility</label>

          <input
            type="text"
            placeholder="⌕"
            value={searchFacility}
            onChange={(e) => setSearchFacility(e.target.value)}
          />

          <div className="hint">
            Hint: Use Indonesian to search
            <br />
            keywords for venues or equipments
          </div>

          <label>Select Date</label>

          <input type="date" />

          <button
            className="check-btn"
            onClick={handleCheck}
          >
            Check
          </button>

          {/* HASIL PENCARIAN */}
          {searchResult.length > 0 && (
            <div className="search-results">

              <h3>Available Facilities</h3>

              {searchResult.map((facility) => (
                <div
                  className="result-item"
                  key={facility.id}
                >
                  <strong>{facility.name}</strong>

                  <span>
                    {facility.location}
                  </span>

                  <span>
                    Capacity: {facility.capacity}
                  </span>
                </div>
              ))}

            </div>
          )}

          {/* JIKA TIDAK DITEMUKAN */}
          {searchFacility.trim() !== '' &&
            searchResult.length === 0 && (
              <p className="no-result">
                Facility not found.
              </p>
            )}

        </div>

      </section>


      {/* ANNOUNCEMENTS */}
      <section className="section announcements">

        <h2>Announcements</h2>

        <div className="section-line"></div>

        <div className="announcement-grid">

          <div className="announcement-card">
            <p>03/09/2026</p>

            <h3>
              Jadwal Pemeliharaan Aula
            </h3>

            <span>
              Aula utama akan ditutup sementara
              untuk pemeliharaan rutin.
            </span>

            <a href="#">
              Read more ›
            </a>
          </div>


          <div className="announcement-card">
            <p>03/09/2026</p>

            <h3>
              Fitur Reservasi Baru
            </h3>

            <span>
              Sekarang kamu bisa cek ketersediaan
              fasilitas langsung dari halaman utama.
            </span>

            <a href="#">
              Read more ›
            </a>
          </div>


          <div className="announcement-card">
            <p>03/09/2026</p>

            <h3>
              Jam Operasional Berubah
            </h3>

            <span>
              Jam operasional gedung diperbarui
              mulai bulan ini.
            </span>

            <a href="#">
              Read more ›
            </a>
          </div>

        </div>

      </section>


      {/* HOW TO USE */}
      <section className="section how-to">

        <h2>How to Use</h2>

        <div className="section-line"></div>

        <div className="steps">

          <div className="step-card">

            <div className="number">
              1
            </div>

            <h3>
              Find your venue
            </h3>

            <p>
              Search and select the
              space you want to book
            </p>

          </div>


          <div className="step-card">

            <div className="number">
              2
            </div>

            <h3>
              Choose your date & time
            </h3>

            <p>
              Pick your preferred
              schedule for the reservation
            </p>

          </div>


          <div className="step-card">

            <div className="number">
              3
            </div>

            <h3>
              Confirm & Done
            </h3>

            <p>
              Finalize your order
            </p>

          </div>

        </div>

      </section>


      {/* FAQ */}
      <section className="section faq">

        <h2>
          Frequently Asked Questions
        </h2>

        <div className="section-line"></div>

        <div className="faq-list">

          <details open>

            <summary>
              Bagaimana cara reservasi fasilitas?
              <span>×</span>
            </summary>

            <div className="faq-answer">
              Pilih fasilitas yang ingin digunakan,
              tentukan tanggal dan waktu, kemudian
              lakukan reservasi.
            </div>

          </details>


          <details>

            <summary>
              Apakah reservasi bisa dibatalkan?
              <span>□</span>
            </summary>

            <div className="faq-answer">
              Ya, reservasi dapat dibatalkan sesuai
              dengan ketentuan yang berlaku.
            </div>

          </details>


          <details>

            <summary>
              Berapa lama proses persetujuan reservasi?
              <span>□</span>
            </summary>

            <div className="faq-answer">
              Reservasi akan diproses oleh petugas
              setelah pengajuan dilakukan.
            </div>

          </details>

        </div>

      </section>


      {/* FOOTER */}
      <footer>

        <div className="footer-content">

          <div>

            <h2>
              About Chloe
            </h2>

            <p>
              Chloe is the premier digital hub
              for booking campus venues and facilities,
              designed to make event planning seamless
              for students and staff.
            </p>

          </div>


          <div>

            <h2>
              Fast Links
            </h2>

            <a href="#">
              Home
            </a>

            <a href="#">
              Booking
            </a>

            <a href="#">
              Reports
            </a>

          </div>

        </div>

      </footer>

    </div>
  )
}

export default App