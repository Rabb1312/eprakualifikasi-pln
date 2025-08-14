<template>
    <div class="login-bg">
        <button class="back-home" @click="goHome">
            <i class="fas fa-arrow-left"></i> Kembali ke Beranda
        </button>
        <div class="login-container">
            <!-- Side Info - tetap sama -->
            <div class="login-left">
                <div class="logo-section">
                    <div class="logo">
                        <img src="/assets/footer_logo.png" alt="Logo PLN" />
                    </div>
                </div>
                <div class="welcome-text">
                    <h1>Selamat Datang</h1>
                    <p style="font-style: italic">
                        Become a Trusted EPC Energy Company
                    </p>
                </div>
                <div class="features-list">
                    <div class="feature-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>Keamanan Data Terjamin</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-clock"></i>
                        <span>Proses Verifikasi Cepat</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-file"></i>
                        <span>Dokumen Digital Terintegrasi</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-chart-line"></i>
                        <span>Tracking Status Real-time</span>
                    </div>
                </div>
            </div>

            <!-- Login Form -->
            <div class="login-right">
                <div class="login-header">
                    <h2>Login</h2>
                    <p>Masuk ke akun Anda untuk melanjutkan</p>
                </div>

                <form class="login-form" @submit.prevent="login">
                    <div
                        v-if="alertMsg"
                        :class="['alert', alertTypeClass]"
                        v-html="alertMsg"
                    />

                    <div class="form-group">
                        <label for="username">Username atau Email</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user input-icon"></i>
                            <input
                                v-model="username"
                                id="username"
                                type="text"
                                required
                                placeholder="Masukkan Email atau Username"
                                autocomplete="username"
                            />
                        </div>
                        <small
                            style="
                                color: #666;
                                font-size: 12px;
                                margin-top: 5px;
                                display: block;
                            "
                        >
                            Anda bisa login menggunakan email atau username
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input
                                :type="showPassword ? 'text' : 'password'"
                                v-model="password"
                                id="password"
                                placeholder="Password"
                                required
                                autocomplete="current-password"
                            />
                            <i
                                :class="[
                                    'fas',
                                    showPassword ? 'fa-eye-slash' : 'fa-eye',
                                    'password-toggle',
                                ]"
                                @click="showPassword = !showPassword"
                            ></i>
                        </div>
                    </div>

                    <div class="remember-forgot">
                        <label class="remember-me">
                            <input type="checkbox" v-model="remember" />
                            <span>Ingat saya</span>
                        </label>
                        <router-link
                            class="forgot-password"
                            to="/forgot-password"
                        >
                            Lupa password?
                        </router-link>
                    </div>

                    <button class="login-btn" :disabled="loading">
                        <span v-if="!loading">Masuk</span>
                        <span v-else>
                            <i class="fas fa-spinner fa-spin"></i> Loading...
                        </span>
                    </button>
                </form>

                <!-- FIXED: Resend Verification Section -->
                <div v-if="showResendLink" class="resend-verification">
                    <div class="resend-header">
                        <i class="fas fa-envelope-open-text"></i>
                        <span>Email Belum Diverifikasi</span>
                    </div>
                    <p>Belum menerima email verifikasi?</p>
                    <button
                        @click="resendVerification"
                        :disabled="loadingResend"
                        class="resend-btn"
                    >
                        <span v-if="!loadingResend">
                            <i class="fas fa-paper-plane"></i>
                            Kirim Ulang Email Verifikasi
                        </span>
                        <span v-else>
                            <i class="fas fa-spinner fa-spin"></i>
                            Mengirim...
                        </span>
                    </button>
                </div>

                <div class="register-link">
                    <p>
                        Belum punya akun?
                        <router-link to="/registration"
                            >Daftar sebagai vendor</router-link
                        >
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

// Reactive variables
const username = ref("");
const password = ref("");
const remember = ref(false);
const showPassword = ref(false);
const alertMsg = ref("");
const alertType = ref("error");
const loading = ref(false);
const router = useRouter();
const showResendLink = ref(false);
const loadingResend = ref(false);

// Set base URL untuk domain .test (Laravel Valet)
axios.defaults.baseURL = "http://eprakualifikasi-pln.test";

// Set default headers
axios.defaults.headers.common["Accept"] = "application/json";
axios.defaults.headers.common["Content-Type"] = "application/json";

// Check if user already logged in
onMounted(() => {
    const token = localStorage.getItem("token");
    const user = localStorage.getItem("user");

    if (token && user) {
        const userData = JSON.parse(user);
        // Set authorization header
        axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;

        // Redirect based on user level
        if (userData.level === "admin") {
            router.push("/admin/dashboard");
        } else {
            router.push("/vendor/dashboard");
        }
    }
});

const alertTypeClass = computed(() => {
    return {
        error: "alert-error",
        success: "alert-success",
        info: "alert-info",
        warning: "alert-warning",
    }[alertType.value];
});

function showAlert(type, msg) {
    alertType.value = type;
    alertMsg.value = msg;
    setTimeout(() => {
        alertMsg.value = "";
    }, 8000);
}

function goHome() {
    router.push("/");
}

async function login() {
    // Validation
    if (!username.value || !password.value) {
        showAlert("error", "Username/Email dan password harus diisi!");
        return;
    }

    loading.value = true;
    alertMsg.value = "";

    try {
        console.log(
            "Attempting login to:",
            axios.defaults.baseURL + "/api/auth/login"
        );
        console.log("Login data:", {
            username: username.value,
            password: "***hidden***",
        });

        const response = await axios.post("/api/auth/login", {
            username: username.value,
            password: password.value,
        });

        console.log("Login response:", response.data);

        if (response.data.success) {
            // Simpan token dan user data
            localStorage.setItem("token", response.data.data.token);
            localStorage.setItem(
                "user",
                JSON.stringify(response.data.data.user)
            );

            // Simpan vendor data jika ada
            if (response.data.data.vendor) {
                localStorage.setItem(
                    "vendor",
                    JSON.stringify(response.data.data.vendor)
                );
            }

            // Set default Authorization header untuk request selanjutnya
            axios.defaults.headers.common[
                "Authorization"
            ] = `Bearer ${response.data.data.token}`;

            showAlert("success", "Login berhasil! Mengalihkan...");

            // Redirect berdasarkan level user
            setTimeout(() => {
                if (response.data.data.user.level === "admin") {
                    router.push("/admin/dashboard");
                } else {
                    router.push("/vendor/dashboard");
                }
            }, 1500);
        } else {
            showAlert("error", response.data.message || "Login gagal");
        }
    } catch (error) {
        console.error("Login error:", error);

        if (error.response) {
            // Server responded with error status
            const status = error.response.status;
            const errorData = error.response.data;

            console.log("Error response:", errorData);

            let errorMessage = "Login gagal";

            if (status === 401) {
                errorMessage =
                    errorData.message || "Username atau password salah";
            } else if (status === 403) {
                errorMessage =
                    errorData.message ||
                    "Akun belum diverifikasi atau tidak aktif";

                // Jika email belum diverifikasi
                if (errorData.requires_verification) {
                    errorMessage +=
                        "<br><small>Silakan cek email Anda untuk link verifikasi</small>";
                }
            } else if (status === 422) {
                // Validation errors
                if (errorData.errors) {
                    const errors = Object.values(errorData.errors).flat();
                    errorMessage = errors.join("<br>");
                } else {
                    errorMessage =
                        errorData.message || "Data yang dimasukkan tidak valid";
                }
            } else if (status >= 500) {
                errorMessage =
                    "Terjadi kesalahan server. Silakan coba lagi nanti.";
            }

            showAlert("error", errorMessage);
        } else if (error.request) {
            // Network error
            showAlert(
                "error",
                "Tidak dapat terhubung ke server. Pastikan:<br>" +
                    "• Server Laravel berjalan di http://eprakualifikasi-pln.test<br>" +
                    "• Koneksi internet stabil<br>" +
                    "• CORS dikonfigurasi dengan benar"
            );
        } else {
            // Other errors
            showAlert("error", "Terjadi kesalahan. Silakan coba lagi.");
        }
    } finally {
        loading.value = false;
    }
}

async function resendVerification() {
    if (!username.value) {
        showAlert("error", "Masukkan email/username terlebih dahulu");
        return;
    }

    loadingResend.value = true;

    try {
        // Extract email if it's email format, otherwise show error
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const email = emailRegex.test(username.value) ? username.value : null;

        if (!email) {
            showAlert(
                "error",
                "Silakan masukkan alamat email untuk kirim ulang verifikasi"
            );
            return;
        }

        const response = await axios.post("/api/auth/resend-verification", {
            email: email,
        });

        if (response.data.success) {
            showAlert(
                "success",
                "Email verifikasi berhasil dikirim ulang! Silakan cek kotak masuk Anda."
            );
            showResendLink.value = false;
        } else {
            showAlert("error", response.data.message);
        }
    } catch (error) {
        showAlert(
            "error",
            error.response?.data?.message || "Gagal mengirim email verifikasi"
        );
    } finally {
        loadingResend.value = false;
    }
}

// Function to logout (clear stored data)
function logout() {
    localStorage.removeItem("token");
    localStorage.removeItem("user");
    localStorage.removeItem("vendor");
    delete axios.defaults.headers.common["Authorization"];
    router.push("/login");
}

// Expose logout function for use in other components
window.logout = logout;
</script>

<style scoped>
/* CSS tetap sama seperti sebelumnya */
.login-bg {
    min-height: 100vh;
    background: linear-gradient(rgba(30, 60, 114, 0.6), rgba(30, 60, 114, 0.6)),
        url("/assets/pln1.jpg");
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    padding: 20px;
    font-family: "Montserrat", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.back-home {
    position: absolute;
    top: 20px;
    left: 20px;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    font-weight: 600;
    z-index: 3;
    transition: all 0.3s;
}
.back-home:hover {
    background: rgba(255, 255, 255, 0.3);
}

.login-container {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    width: 100%;
    max-width: 1000px;
    min-height: 600px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    position: relative;
    z-index: 2;
}

.login-left {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
    padding: 60px 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    position: relative;
}

.logo-section {
    position: relative;
    z-index: 1;
}
.logo {
    width: 300px;
    border-radius: 15px;
    overflow: hidden;
    margin: 0 auto 20px;
    background: transparent;
    display: flex;
    align-items: center;
    justify-content: center;
}
.logo img {
    width: 300px;
    max-width: 100%;
    object-fit: cover;
}
.welcome-text h1 {
    font-size: 2.2em;
    margin-bottom: 10px;
    font-weight: 700;
}
.features-list {
    margin-top: 30px;
    text-align: left;
}
.feature-item {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    font-size: 1em;
}
.feature-item i {
    margin-right: 10px;
    color: #ffd700;
    font-size: 1.2em;
}

.login-right {
    padding: 60px 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    box-sizing: border-box;
    overflow: hidden;
}
.login-header {
    text-align: center;
    margin-bottom: 40px;
}
.login-header h2 {
    color: #1e3c72;
    font-size: 2em;
    margin-bottom: 10px;
}
.login-header p {
    color: #666;
    font-size: 1.1em;
}

.login-form {
    margin-bottom: 30px;
}
.form-group {
    margin-bottom: 25px;
    position: relative;
    width: 100%;
    box-sizing: border-box;
}
.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #1e3c72;
}
.input-wrapper {
    position: relative;
    width: 100%;
    box-sizing: border-box;
}
.form-group input {
    width: 100%;
    padding: 15px 20px 15px 50px;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    font-size: 16px;
    background: #f8f9fa;
    transition: all 0.3s;
    box-sizing: border-box;
    max-width: 100%;
}
.form-group input:focus {
    border-color: #1e3c72;
    background: white;
    box-shadow: 0 0 0 3px rgba(30, 60, 114, 0.1);
    outline: none;
}
.input-icon {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
    font-size: 1.1em;
}
.password-toggle {
    position: absolute;
    right: 18px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #666;
    font-size: 1.1em;
}

.remember-forgot {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}
.remember-me {
    display: flex;
    align-items: center;
    gap: 8px;
}
.remember-me input[type="checkbox"] {
    width: auto;
}
.forgot-password {
    color: #1e3c72;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s;
}
.forgot-password:hover {
    color: #ffd700;
}

.login-btn {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    margin-bottom: 20px;
    position: relative;
}
.login-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}
.login-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(30, 60, 114, 0.3);
}

.register-link {
    text-align: center;
    color: #666;
}
.register-link a {
    color: #1e3c72;
    text-decoration: none;
    font-weight: 600;
}
.register-link a:hover {
    color: #ffd700;
}

.alert {
    padding: 12px 16px;
    margin-bottom: 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    display: block;
}
.alert-error {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}
.alert-success {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}
.alert-info {
    background-color: #cce7ff;
    border: 1px solid #b8daff;
    color: #004085;
}
.alert-warning {
    background-color: #fff3cd;
    border: 1px solid #ffeaa7;
    color: #856404;
}

@media (max-width: 768px) {
    .login-container {
        grid-template-columns: 1fr;
        max-width: 400px;
    }
    .login-left {
        display: none;
    }
    .login-right {
        padding: 40px 30px;
    }
    .login-bg {
        padding: 0;
    }
}

.resend-verification {
    margin: 20px 0;
    padding: 20px;
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border: 1px solid #ffd700;
    border-radius: 12px;
    text-align: center;
    animation: slideIn 0.3s ease-in-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.resend-header {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-bottom: 10px;
    font-weight: 600;
    color: #856404;
}

.resend-header i {
    font-size: 1.2em;
}

.resend-verification p {
    color: #856404;
    margin-bottom: 15px;
    font-size: 14px;
}

.resend-btn {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    color: #212529;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.resend-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 193, 7, 0.3);
}

.resend-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

/* Update existing link-btn styles */
.link-btn {
    background: none;
    border: none;
    color: #1e3c72;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    font-size: 14px;
    transition: color 0.3s;
}

.link-btn:hover {
    color: #ffd700;
}

.link-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>
