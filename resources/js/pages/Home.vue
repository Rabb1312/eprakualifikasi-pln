<template>
  <div class="home-container">
    <!-- Header -->
    <header class="header">
      <div class="header-content">
        <div class="logo-header">
          <img src="/assets/footer_logo.png" alt="Logo PLN" class="logo-header-img" />
        </div>
        <nav>
          <ul class="nav-menu">
            <li><a href="#home" @click="scrollTo('home')">Beranda</a></li>
            <li><a href="#features" @click="scrollTo('features')">Fitur</a></li>
            <li><a href="#footer" @click="scrollTo('footer')">Kontak</a></li>
          </ul>
        </nav>
        <button class="login-btn" @click="goToLogin">
          <i class="fas fa-sign-in-alt"></i> Login
        </button>
      </div>
    </header>

    <!-- Hamburger Button (fixed, always visible) -->
    <button class="hamburger-btn" @click="openSidebar">
      <span></span><span></span><span></span>
    </button>

    <!-- Sidebar Overlay -->
    <div v-if="isOpen" class="sidebar-overlay" @click.self="closeSidebar">
      <div class="sidebar-panel">
        <button class="close-btn-siderbar" @click="closeSidebar"><i class="fas fa-times"></i></button>
        <div class="sidebar-content">
          <!-- Logo -->
          <div class="sidebar-logo">
            <img src="/assets/footer_logo.png" alt="PLN Logo" />
          </div>
          <!-- Contacts -->
          <h2 class="sidebar-title">Our Contacts</h2>
          <div class="sidebar-contact-list">
            <div class="sidebar-contact-item">
              <i class="fas fa-map-marker-alt contact-icon green"></i>
              <div>
                Building 18 Office Park Lt. 18<br>
                Jl. T.B. Simatupang No.18,<br>
                Pasar Minggu, South Jakarta - 12520<br>
                Indonesia
              </div>
            </div>
            <div class="sidebar-contact-item">
              <i class="fas fa-envelope contact-icon green"></i>
              <div>
                perencanaan@plnnpc.com
              </div>
            </div>
            <div class="sidebar-contact-item">
              <i class="fas fa-phone contact-icon green"></i>
              <div>
                +62 (021) 2787 1818<br>
                +62 (021) 2770 6558
              </div>
            </div>
          </div>
          <!-- Social Media -->
          <div class="sidebar-socials">
            <a href="#" aria-label="Instagram" class="social-icon ig"><i class="fab fa-instagram"></i></a>
            <a href="#" aria-label="Twitter" class="social-icon tw"><i class="fab fa-twitter"></i></a>
            <a href="#" aria-label="Facebook" class="social-icon fb"><i class="fab fa-facebook-f"></i></a>
            <a href="#" aria-label="LinkedIn" class="social-icon li"><i class="fab fa-linkedin-in"></i></a>
          </div>
        </div>
      </div>
    </div>

    <!-- Hero Section with Slideshow -->
    <section class="hero" id="home">
      <div class="hero-slideshow">
        <div 
          v-for="(slide, index) in slides" 
          :key="index"
          class="hero-slide"
          :class="{ active: currentSlideIndex === index }"
          :style="{ backgroundImage: `url(${slide.image})` }"
        ></div>
      </div>

      <!-- Navigation Arrows -->
      <button class="slideshow-nav prev" @click="changeSlide(-1)">
        <i class="fas fa-chevron-left"></i>
      </button>
      <button class="slideshow-nav next" @click="changeSlide(1)">
        <i class="fas fa-chevron-right"></i>
      </button>

      <!-- Navigation Dots -->
      <div class="slideshow-dots">
        <span 
          v-for="(slide, index) in slides"
          :key="index"
          class="dot"
          :class="{ active: currentSlideIndex === index }"
          @click="goToSlide(index)"
        ></span>
      </div>

      <div class="hero-content">
        <h1>E-Prakualifikasi</h1>
        <p>Portal online registrasi dan pengelolaan vendor untuk mendapatkan vendor <br>yang berkompeten dan terkualifikasi <strong>PLN Nusantara Power Construction</strong> </p>
        <div class="cta-buttons">
          <router-link to="/registration" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Daftar Sebagai Vendor
          </router-link>
          <!-- <router-link to="/guide" class="btn btn-secondary">
            <i class="fas fa-book"></i> Panduan Pendaftaran
          </router-link> -->
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
      <div class="container">
        <div class="section-title">
          <h2>Fitur Unggulan</h2>
          <p>Sistem yang dirancang untuk memudahkan proses prakualifikasi vendor</p>
        </div>
        <div class="features-grid">
          <div 
            v-for="feature in features" 
            :key="feature.id"
            class="feature-card"
          >
            <div class="feature-icon">
              <i :class="feature.icon"></i>
            </div>
            <h3>{{ feature.title }}</h3>
            <p>{{ feature.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Info Modal -->
    <div v-if="showModal" class="info-modal" @click="closeModal">
  <div class="modal-content" @click.stop>
    <div class="modal-header">
      <h2>
        <span class="icon"><i class="fas fa-info-circle"></i></span>
        Vendor Management System (VMS)
      </h2>
      <button class="close-btn" @click="closeModal">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <div class="modal-body">
      <!-- Penjelasan VMS -->
      <div class="info-section featured">
        <h3><i class="fas fa-network-wired"></i>Tentang E-Prakualifikasi</h3>
        <div class="featured-content">
          <p>
            <strong>E-Prakualifikasi</strong> merupakan <span class="highlight">portal online</span> 
            sebagai sistem pengelolaan vendor yang menjadi bagian dari tahapan awal proses pengadaan 
            barang dan jasa PT PLN Nusantara Power Construction. Sistem ini dirancang untuk mendapatkan 
            vendor yang <span class="highlight">kompeten dan terkualifikasi</span> sebagai mitra kerja 
            strategis PT PLN NPC.
          </p>
        </div>
      </div>

      <!-- Persyaratan Umum -->
      <div class="info-section">
        <h3><i class="fas fa-clipboard-list"></i>Persyaratan Umum Calon Vendor</h3>
        <ul class="info-list requirements">
          <li>
            <i class="fas fa-building text-blue"></i>
            <strong>Status Badan Hukum:</strong> Harus merupakan badan hukum yang sah dan terdaftar 
            sesuai peraturan perundang-undangan di Indonesia
          </li>
          <li>
            <i class="fas fa-shield-alt text-red"></i>
            <strong>Integritas Dokumen:</strong> Dilarang keras melakukan manipulasi, pemalsuan, 
            atau pengubahan dokumen. Pelanggaran berakibat <span class="highlight">diskualifikasi permanen</span>
          </li>
          <li>
            <i class="fas fa-ban text-orange"></i>
            <strong>Sanksi Blacklist:</strong> Vendor yang melanggar ketentuan akan dikenakan sanksi 
            blacklist dan tidak dapat berpartisipasi dalam pengadaan PT PLN NPC
          </li>
          <li>
            <i class="fas fa-envelope text-green"></i>
            <strong>Kebijakan Akun:</strong> Satu alamat email hanya untuk satu akun perusahaan. 
            <span class="highlight">Pendaftaran ganda tidak diperkenankan</span>
          </li>
          <li>
            <i class="fas fa-file-check text-purple"></i>
            <strong>Kelengkapan Data:</strong> Wajib melengkapi seluruh data dan dokumen yang 
            dipersyaratkan dengan informasi yang akurat dan terkini
          </li>
          <li>
            <i class="fas fa-award text-gold"></i>
            <strong>Komitmen Kualitas:</strong> Memiliki komitmen terhadap kualitas, keselamatan kerja, 
            dan standar lingkungan PT PLN NPC
          </li>
        </ul>
      </div>

      <!-- Layanan VMS -->
      <div class="info-section">
        <h3><i class="fas fa-cogs"></i>Layanan dalam VMS</h3>
        <div class="services-grid">
          <div class="service-item">
            <i class="fas fa-clipboard-check"></i>
            <h4>e-Prakualifikasi</h4>
            <p>Pendaftaran dan verifikasi vendor secara online</p>
          </div>
          <div class="service-item">
            <i class="fas fa-users-cog"></i>
            <h4>Vendor Management</h4>
            <p>Pengelolaan database vendor terkualifikasi</p>
          </div>
          <div class="service-item">
            <i class="fas fa-star"></i>
            <h4>Performance Evaluation</h4>
            <p>Penilaian kinerja vendor secara berkala</p>
          </div>
          <div class="service-item">
            <i class="fas fa-handshake"></i>
            <h4>Partnership Management</h4>
            <p>Pengelolaan kemitraan strategis jangka panjang</p>
          </div>
        </div>
      </div>

      <!-- Company Footer -->
      <div class="company-footer">
        <div class="company-info">
          <h4>PT PLN Nusantara Power Construction</h4>
          <p>Trusted EPC Energy Company</p>
        </div>
      </div>
    </div>

    <div class="modal-footer">
      <button class="modal-btn" @click="closeModal">
        <i class="fas fa-check"></i> Saya Mengerti
      </button>
      <button class="modal-btn secondary" @click="goToRegistration">
        <i class="fas fa-user-plus"></i> Daftar Sekarang
      </button>

      <div class="dont-show-again">
        <input type="checkbox" id="dontShowAgain" v-model="dontShowAgain">
        <label for="dontShowAgain">Jangan tampilkan lagi</label>
      </div>
    </div>
  </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
    <div class="footer-container">
      <!-- Company Info Section -->
      <div class="footer-section company-info">
        <div class="logo-footer">
          <img src="/assets/footer_logo.png" alt="PLN Logo" class="footer-logo-wide" />
        </div>
        <div>
          <span class="company-name">PT PLN NPC</span>
          <div class="company-subtitle">Nusantara Power Construction</div>
          <p class="company-quote"><em>"Become a Trusted EPC Energy Company"</em></p>
        </div>
        <div class="footer-social">
          <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
          <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
          <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
          <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
        </div>
      </div>

      <!-- Contacts Section -->
      <div class="footer-section" id="footer">
        <h3>Contacts</h3>
        <div class="contact-item">
          <i class="fas fa-map-marker-alt"></i>
          <div>
            <strong>Building 18 Office Park Lt. 18</strong><br />
            Jl. T.B. Simatupang No.18,<br />
            Pasar Minggu,<br />
            South Jakarta - 12520<br />
            Indonesia
          </div>
        </div>
        <div class="contact-item">
          <i class="fas fa-envelope"></i>
          <div>
            <a href="mailto:perencanaan@plnnpc.com">perencanaan@plnnpc.com</a>
          </div>
        </div>
        <div class="contact-item">
          <i class="fas fa-phone"></i>
          <div>
            <a href="tel:+622127871818">+62 (021) 2787 1818</a><br />
            <a href="tel:+622127706558">+62 (021) 2770 6558</a>
          </div>
        </div>
      </div>

      <!-- About Section -->
      <div class="footer-section">
        <h3>About</h3>
        <ul class="footer-links">
          <li><a href="#">Milestone</a></li>
          <li><a href="#">Vision & Mission</a></li>
          <li><a href="#">Organizational Structure</a></li>
          <li><a href="#">Board of Director</a></li>
          <li><a href="#">Board of Commissioner</a></li>
        </ul>
        <ul class="footer-links">
          <li><a href="#">Profile</a></li>
          <li><a href="#">Achievement</a></li>
          <li><a href="#">Subsidiary Company</a></li>
          <li><a href="#">Annual Report</a></li>
          <li><a href="#">Good Corporate Governance</a></li>
        </ul>
      </div>

      <!-- Subscribe & Map Section -->
      <div class="footer-section subscribe-section">
        <h3>Subscribe</h3>
        <p class="subscribe-text">Subscribe to our newsletter!<br />Stay always in touch!</p>
        <div class="newsletter-form">
          <div class="input-group">
            <input type="email" placeholder="Your Email" class="newsletter-input" />
            <button type="submit" class="newsletter-btn" aria-label="Subscribe">
              <i class="fa-jelly fa-regular fa-envelope"></i>
            </button>
          </div>
          <p class="privacy-note">* Don't worry, we don't spam.</p>
        </div>
        <div class="map-container">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.7282343173265!2d106.82974567491809!3d-6.299397393689742!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f41ec16cb343%3A0x879310cc71b54f0f!2sPT%20PLN%20Nusantara%20Power%20Construction!5e0!3m2!1sid!2sid!4v1754450017112!5m2!1sid!2sid"
            width="100%"
            height="140"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
          ></iframe>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>
        Copyright © 2025 <strong>PT PLN Nusantara Power Construction</strong> All Rights Reserved
      </p>
    </div>
    </footer>
  </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'

export default {
  name: 'Home',
  setup() {
    const router = useRouter()
    // Sidebar state
    const isOpen = ref(false)
    function openSidebar() { isOpen.value = true }
    function closeSidebar() { isOpen.value = false }

    // Reactive data (hero, features, dsb)
    const currentSlideIndex = ref(0)
    const showModal = ref(false)
    const dontShowAgain = ref(false)
    const slideInterval = ref(null)

    const slides = ref([
      { image: '/assets/pln1.jpg' },
      { image: '/assets/pln2.jpg' },
      { image: '/assets/pln3.jpg' }
    ])
    const features = ref([
      { id: 1, icon: 'fas fa-shield-alt', title: 'Keamanan Terjamin', description: 'Sistem keamanan berlapis dengan enkripsi data dan autentikasi multi-faktor' },
      { id: 2, icon: 'fas fa-file', title: 'Dokumen Digital', description: 'Upload dan kelola dokumen secara digital dengan sistem yang terintegrasi' },
      { id: 3, icon: 'fas fa-chart-line', title: 'Tracking Real-time', description: 'Pantau status pengajuan prakualifikasi secara real-time' }
    ])

    const showSlide = (index) => { currentSlideIndex.value = index }
    const nextSlide = () => { const nextIndex = (currentSlideIndex.value + 1) % slides.value.length; showSlide(nextIndex) }
    const changeSlide = (direction) => { direction === 1 ? nextSlide() : showSlide((currentSlideIndex.value - 1 + slides.value.length) % slides.value.length); resetAutoSlide() }
    const goToSlide = (index) => { showSlide(index); resetAutoSlide() }
    const startAutoSlide = () => { slideInterval.value = setInterval(nextSlide, 5000) }
    const resetAutoSlide = () => { if (slideInterval.value) clearInterval(slideInterval.value); startAutoSlide() }

    const scrollTo = (elementId) => {
      const element = document.getElementById(elementId)
      if (element) element.scrollIntoView({ behavior: 'smooth' })
    }
    const goToLogin = () => { router.push('/login') }
    const closeModal = () => { if (dontShowAgain.value) localStorage.setItem('hideInfoModal', 'true'); showModal.value = false }
    const goToRegistration = () => { closeModal(); router.push('/registration') }

    // Lifecycle hooks
    onMounted(() => {
      startAutoSlide()
      const hideModal = localStorage.getItem('hideInfoModal')
      if (!hideModal) setTimeout(() => { showModal.value = true }, 1000)
      const handleScroll = () => {
        const header = document.querySelector('.header');
        if (window.scrollY > 100) {
          header.style.background = 'white'
          header.style.color = 'white'
          header.style.backdropFilter = 'blur(10px)'
        } else {
          header.style.background = 'white'
          header.style.color = '#1e3c72'
          header.style.backdropFilter = 'none'
        }
      }
      window.addEventListener('scroll', handleScroll)
      onUnmounted(() => {
        if (slideInterval.value) clearInterval(slideInterval.value)
        window.removeEventListener('scroll', handleScroll)
      })
    })

    return {
      isOpen,
      openSidebar,
      closeSidebar,
      currentSlideIndex,
      slides,
      features,
      showModal,
      dontShowAgain,
      changeSlide,
      goToSlide,
      scrollTo,
      goToLogin,
      closeModal,
      goToRegistration
    }
  }
}
</script>

<style scoped>
/* Reset and base styles */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.home-container {
  font-family: 'Montserrat', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  line-height: 1.6;
  color: #333;
  background: #f8f9fa;
}

/* Header */
.header {
  background: white;
  color: #1e3c72;
  padding: 10px 0;
  position: fixed;
  width: 100%;
  top: 0;
  z-index: 1000;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.header-content {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 20px;
  color: white;
}

.logo {
  display: flex;
  align-items: center;
  gap: 15px;
}

.logo-header {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  justify-content: flex-start;
  gap: 6px;
  background: transparent;
  padding: 0;
}

.logo-header-img {
  width: 190px;         
  max-width: 100%;
  height: auto;
  display: block;
  background: transparent;
  box-shadow: none;
  border-radius: 0;
}

.logo-header-text {
  font-size: 1.13em;
  font-weight: bold;
  color: #1e3c72;
  letter-spacing: 0.5px;
  margin-top: 2px;
  margin-left: 4px;
}

.logo-img {
  width: 50px;
  height: 50px;
  border-radius: 8px;
  padding: 5px;
}

.logo-text {
  font-size: 1.5em;
  font-weight: bold;
}

.nav-menu {
  display: flex;
  list-style: none;
  gap: 30px;
}

.nav-menu a {
  color: #1e3c72;
  text-decoration: none;
  font-weight: 500;
  transition: all 0.3s ease;
  cursor: pointer;
}

.nav-menu a:hover {
  color: #ffd700;
}

.login-btn {
  background: #ffd700;
  color: #1e3c72;
  padding: 10px 20px;
  border: none;
  border-radius: 25px;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.3s ease;
}

.login-btn:hover {
  background: #ffed4a;
  transform: translateY(-2px);
}

.hamburger-btn {
  position: fixed;
  top: 10px;
  right: 26px;
  z-index: 9999;
  background: transparent;
  border: none;
  cursor: pointer;
  display: flex;
  flex-direction: column;
  gap: 5px;
  width: 38px;
  height: 38px;
  justify-content: center;
  align-items: center;
}
.hamburger-btn span {
  display: block;
  width: 28px;
  height: 4px;
  background: #1e3c72;
  border-radius: 2px;
  transition: all 0.3s;
}
.sidebar-overlay {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0,0,0,0.2);
  z-index: 99999;
  display: flex;
}
.sidebar-panel {
  background: #fff;
  width: 360px;
  max-width: 100vw;
  height: 100vh;
  padding: 0;
  position: fixed; 
  top: 0;
  right: 0;       
  box-shadow: -2px 0 18px rgba(0,0,0,0.17);
  animation: sidebarIn 0.25s cubic-bezier(.46,.03,.52,.96);
  display: flex;
  flex-direction: column;
}
@keyframes sidebarIn {
  from { transform: translateX(100px); opacity: 0 }
  to   { transform: translateX(0); opacity: 1 }
}
.close-btn-siderbar {
  position: absolute;
  top: 20px;
  right: 18px; /* dari left: 18px */
  background: #212121;
  color: #fff;
  border: none;
  font-size: 1.6em;
  border-radius: 6px;
  width: 40px;
  height: 40px;
  cursor: pointer;
  z-index: 100;
  display: flex;
  align-items: center;
  justify-content: center;
}
.sidebar-content {
  margin-top: 70px;
  padding: 0 32px;
}
.sidebar-logo {
  margin-bottom: 20px;
  text-align: left;
}
.sidebar-logo img {
  height: 40px;
  width: auto;
  object-fit: contain;
}
.sidebar-title {
  font-size: 1.25em;
  font-weight: bold;
  margin-bottom: 18px;
  margin-top: 10px;
}
.sidebar-contact-list {
  margin-bottom: 24px;
}
.sidebar-contact-item {
  display: flex;
  align-items: flex-start;
  gap: 14px;
  margin-bottom: 16px;
  color: #212121;
  font-size: 1.08em;
}
.contact-icon {
  font-size: 1.3em;
  margin-top: 4px;
}
.contact-icon.green {
  color: #22c55e;
}
.sidebar-socials {
  display: flex;
  gap: 18px;
  margin-top: 50px;
  margin-bottom: 18px;
  margin-left: 65px;
}
.social-icon {
  font-size: 1.35em;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 38px;
  height: 38px;
  border-radius: 50%;
  background: #212121;
  color: #fff;
  transition: background 0.2s;
}
.social-icon.ig { background: #212121; }
.social-icon.tw { background: #29a2f2; }
.social-icon.fb { background: #2a437a; }
.social-icon.li { background: #2383c5; }
.social-icon:hover { opacity: 0.8; }
@media (max-width: 500px) {
  .sidebar-panel { width: 97vw; min-width: 0; }
  .sidebar-content { padding: 0 13px; }
}

/* Hero Section */
.hero {
  position: relative;
  color: white;
  padding: 120px 0 80px;
  text-align: center;
  overflow: hidden;
  height: 100vh;
  min-height: 600px;
}

.hero-slideshow {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 1;
}

.hero-slide {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
  transition: opacity 1.5s ease-in-out;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}

.hero-slide::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(30, 60, 114, 0.6);
  z-index: 1;
}

.hero-slide.active {
  opacity: 1;
}

.hero-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
  position: relative;
  z-index: 10;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.hero h1 {
  font-size: 3.5em;
  margin-bottom: 20px;
  font-weight: 700;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.hero p {
  font-size: 1.3em;
  margin-bottom: 30px;
  opacity: 0.9;
}

.cta-buttons {
  display: flex;
  justify-content: center;
  gap: 20px;
  margin-top: 40px;
}

/* Slideshow Navigation */
.slideshow-dots {
  position: absolute;
  bottom: 30px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 15px;
  z-index: 15;
}

.dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.5);
  cursor: pointer;
  transition: all 0.3s ease;
  border: 2px solid transparent;
}

.dot.active {
  background: #ffd700;
  border-color: white;
  transform: scale(1.2);
}

.slideshow-nav {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(255, 255, 255, 0.2);
  border: none;
  color: white;
  font-size: 24px;
  padding: 15px 20px;
  cursor: pointer;
  transition: all 0.3s ease;
  z-index: 15;
  border-radius: 5px;
}

.slideshow-nav:hover {
  background: rgba(255, 255, 255, 0.3);
}

.prev {
  left: 30px;
}

.next {
  right: 30px;
}

/* Buttons */
.btn {
  padding: 15px 30px;
  border: none;
  border-radius: 50px;
  font-size: 1.1em;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
  display: inline-block;
}

.btn-primary {
  background: #ffd700;
  color: #1e3c72;
}

.btn-primary:hover {
  background: #ffed4a;
  transform: translateY(-3px);
  box-shadow: 0 10px 20px rgba(255, 215, 0, 0.3);
}

.btn-secondary {
  background: transparent;
  color: white;
  border: 2px solid white;
}

.btn-secondary:hover {
  background: white;
  color: #1e3c72;
}

/* Features Section */
.features {
  padding: 80px 0;
  background: white;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.section-title {
  text-align: center;
  margin-bottom: 60px;
}

.section-title h2 {
  font-size: 2.5em;
  color: rgb(52, 139, 219);
  margin-bottom: 10px;
}

.section-title p {
  font-size: 1.2em;
  color: #666;
}

.features-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 40px;
  margin-top: 40px;
}

.feature-card {
  background: #f8f9fa;
  padding: 40px 30px;
  border-radius: 20px;
  text-align: center;
  transition: all 0.3s ease;
  border: 2px solid transparent;
}

.feature-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
  border-color: #ffd700;
}

.feature-icon {
  width: 80px;
  height: 80px;
  background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 20px;
  font-size: 2em;
  color: white;
}

.feature-card h3 {
  font-size: 1.5em;
  color: #1e3c72;
  margin-bottom: 15px;
}

/* Modal Styles */
.info-modal {
  position: fixed;
  z-index: 10000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7);
  backdrop-filter: blur(5px);
}

.modal-content {
  background-color: #fefefe;
  margin: 3% auto;
  padding: 0;
  border-radius: 15px;
  width: 90%;
  max-width: 800px;
  max-height: 85vh;
  overflow-y: auto;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  animation: modalSlideIn 0.4s ease-out;
}

@keyframes modalSlideIn {
  from {
    opacity: 0;
    transform: translateY(-50px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

.modal-header {
  background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
  color: white;
  padding: 25px 30px;
  border-radius: 15px 15px 0 0;
  position: relative;
}

.modal-header h2 {
  margin: 0;
  font-size: 1.8em;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 10px;
}

.close-btn {
  position: absolute;
  right: 20px;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(255, 255, 255, 0.2);
  border: none;
  color: white;
  font-size: 24px;
  cursor: pointer;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}

.close-btn:hover {
  background: rgba(255, 255, 255, 0.3);
}

.modal-body {
  padding: 30px;
  line-height: 1.6;
  color: #333;
}

.info-section {
  margin-bottom: 25px;
}

.info-section h3 {
  color: #1e3c72;
  font-size: 1.3em;
  margin-bottom: 15px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.info-list {
  margin-left: 20px;
  margin-bottom: 15px;
}

.info-list li {
  margin-bottom: 8px;
  position: relative;
  padding-left: 20px;
}

.info-list li::before {
  content: '▶';
  position: absolute;
  left: 0;
  color: #ffd700;
  font-size: 0.8em;
}

.highlight {
  background: linear-gradient(120deg, #ffd700 0%, #ffed4a 100%);
  padding: 2px 6px;
  border-radius: 4px;
  font-weight: 600;
  color: #1e3c72;
}

.modal-footer {
  background: #f8f9fa;
  padding: 20px 30px;
  border-radius: 0 0 15px 15px;
  text-align: center;
  border-top: 1px solid #eee;
}

.modal-btn {
  background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
  color: white;
  padding: 12px 30px;
  border: none;
  border-radius: 25px;
  font-weight: 600;
  cursor: pointer;
  font-size: 16px;
  transition: all 0.3s ease;
  margin: 0 10px;
}

.modal-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(30, 60, 114, 0.3);
}

.modal-btn.secondary {
  background: #6c757d;
}

.dont-show-again {
  margin-top: 15px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  color: #666;
  font-size: 14px;
}

/* Additional styles for new content */
.featured-content {
  background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
  padding: 1.5rem;
  border-radius: 8px;
  border-left: 4px solid #3b82f6;
  margin-top: 1rem;
}

.requirements li {
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
  margin-bottom: 1rem;
  padding: 0.75rem;
  background: #f8fafc;
  border-radius: 6px;
  border-left: 3px solid #e5e7eb;
}

.requirements li i {
  margin-top: 0.25rem;
  width: 16px;
  flex-shrink: 0;
}

.text-blue { color: #3b82f6; }
.text-red { color: #ef4444; }
.text-orange { color: #f97316; }
.text-green { color: #22c55e; }
.text-purple { color: #8b5cf6; }
.text-gold { color: #f59e0b; }

.services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  margin-top: 1rem;
}

.service-item {
  text-align: center;
  padding: 1.5rem;
  background: #f8fafc;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
  transition: transform 0.2s, box-shadow 0.2s;
}

.service-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.service-item i {
  font-size: 2rem;
  color: #3b82f6;
  margin-bottom: 0.75rem;
}

.service-item h4 {
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: #1f2937;
}

.service-item p {
  font-size: 0.875rem;
  color: #6b7280;
}

.company-footer {
  background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
  color: white;
  padding: 1.5rem;
  border-radius: 8px;
  text-align: center;
  margin-top: 1.5rem;
}

.company-info h4 {
  font-weight: 600;
  margin-bottom: 0.25rem;
}

.company-info p {
  opacity: 0.9;
  font-size: 0.875rem;
}

.highlight {
  background: #fef3c7;
  color: #92400e;
  padding: 0.125rem 0.375rem;
  border-radius: 4px;
  font-weight: 500;
}

/* Footer */
.footer {
  background: #1e3c72;
  color: #fff;
  padding: 0;
  font-family: 'Montserrat', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
.footer-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 40px 32px 20px 32px;
  display: flex;
  flex-wrap: wrap;
  gap: 32px;
  justify-content: space-between;
}

.footer-section {
  min-width: 220px;
  flex: 1 1 220px;
  margin-bottom: 10px;
}
.footer-section h3 {
  font-size: 1.11em;
  margin-bottom: 18px;
  font-weight: bold;
  letter-spacing: 0.5px;
}

.logo-footer {
  background: transparent;
  padding: 0;
}
.footer-logo-wide {
  width: 220px;           
  max-width: 100%;        
  height: auto;           
  display: block;
  background: transparent; 
  box-shadow: none;        
  border-radius: 0;       
  margin-bottom: 12px;     
}
.company-name {
  font-weight: bold;
  font-size: 1.1em;
  display: block;
}
.company-subtitle {
  font-size: 0.95em;
  color: #a6b2c9;
  display: block;
  margin-bottom: 8px;
}
.company-quote {
  margin: 10px 0 18px 0;
  font-size: 1em;
  color: #fff;
}

.footer-social a {
  display: inline-block;
  margin-right: 14px;
  font-size: 1.2em;
  color: #a6b2c9;
  transition: color 0.2s;
}
.footer-social a:hover {
  color: #ffd700;
}

.contact-item {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  margin-bottom: 13px;
  font-size: 1em;
}
.contact-item i {
  margin-top: 4px;
  color: #ffd700;
  font-size: 1.2em;
}
.contact-item a {
  color: #a6b2c9;
  text-decoration: none;
  transition: color 0.2s;
}
.contact-item a:hover {
  color: #ffd700;
}

.footer-links {
  list-style: none;
  padding: 0;
  margin-bottom: 10px;
}
.footer-links li {
  margin-bottom: 7px;
}
.footer-links a {
  color: #a6b2c9;
  text-decoration: none;
  font-size: 1em;
  transition: color 0.2s;
}
.footer-links a:hover {
  color: #ffd700;
}

.subscribe-text {
  margin-bottom: 10px;
  font-size: 0.98em;
}
.newsletter-form {
  margin-bottom: 10px;
}
.input-group {
  display: flex;
  align-items: center;
  margin-bottom: 6px;
}
.newsletter-input {
  flex: 1;
  padding: 10px 14px;
  border: none;
  border-radius: 5px 0 0 5px;
  font-size: 1em;
  background: #181b36;
  color: #fff;
}
.newsletter-input::placeholder {
  color: #a6b2c9;
}
.newsletter-btn {
  background: #ff3838;
  border: none;
  color: #fff;
  padding: 10px 14px;
  border-radius: 0 5px 5px 0;
  cursor: pointer;
  font-size: 1.1em;
  transition: background 0.2s;
}
.newsletter-btn:hover {
  background: #ffd700;
  color: #1e3c72;
}

.privacy-note {
  color: #a6b2c9;
  font-size: 0.91em;
  margin-bottom: 10px;
}
.map-container {
  margin-top: 10px;
  width: 100%;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 12px rgba(0,0,0,0.1);
}

/* Footer bottom */
.footer-bottom {
  border-top: 1px solid #b4b0b0;
  padding: 15px 0 10px 0;
  text-align: center;
  color: #a6b2c9;
  font-size: 0.8em;
  background: #1e3c72;
}

/* Responsive */
@media (max-width: 1100px) {
  .footer-container {
    flex-direction: column;
    gap: 16px;
    padding: 36px 14px 16px 14px;
  }
  .footer-section {
    margin-bottom: 18px;
  }
}
@media (max-width: 600px) {
  .footer-container {
    padding: 22px 4px 8px 4px;
  }
  .footer-section {
    min-width: 0;
  }
  .footer-logo {
    width: 52px;
    height: 52px;
  }
}
</style>