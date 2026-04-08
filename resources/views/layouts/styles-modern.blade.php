<style>
/* ========================================
   SISTEMA POS - DASHBOARD ADAPTATIVO
   ======================================= */

/* Reset & Base */
* {
  box-sizing: border-box;
}

:root {
  --primary: #2563eb;
  --primary-dark: #1d4ed8;
  --success: #059669;
  --warning: #d97706;
  --danger: #dc2626;
  --gray-50: #f9fafb;
  --gray-100: #f3f4f6;
  --gray-800: #1f2937;
  --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
  --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
  --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
  --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 10px 10px -5px rgb(0 0 0 / 0.04);
  --transition: all 0.2s ease-in-out;
}

/* Body Optimized */
body {
  background: linear-gradient(135deg, var(--gray-50) 0%, #f8fafc 100%);
  font-family: system-ui, -apple-system, 'Segoe UI', sans-serif;
  line-height: 1.6;
}

/* Card System */
.card {
  border: none;
  border-radius: 16px;
  box-shadow: var(--shadow-md);
  transition: var(--transition);
  background: white;
  overflow: hidden;
}

.card:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-xl);
}

/* Modern Buttons */
.btn {
  border-radius: 12px;
  font-weight: 600;
  border: none;
  padding: 0.75rem 1.5rem;
  transition: var(--transition);
  position: relative;
  overflow: hidden;
}

.btn-primary {
  background: linear-gradient(135deg, var(--primary), var(--primary-dark));
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);
}

.btn-success {
  background: linear-gradient(135deg, var(--success), #10b981);
}

/* Form Controls */
.form-control {
  border: 2px solid var(--gray-100);
  border-radius: 12px;
  padding: 0.875rem 1.25rem;
  transition: var(--transition);
  font-size: 0.95rem;
}

.form-control:focus {
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
  transform: translateY(-1px);
}

/* Tables Modern */
.table {
  margin-bottom: 0;
}

.table th {
  background: linear-gradient(135deg, var(--gray-100), #f1f5f9);
  font-weight: 600;
  border: none;
  color: var(--gray-800);
  padding: 1.25rem 1rem;
  text-transform: uppercase;
  font-size: 0.8rem;
  letter-spacing: 0.05em;
}

.table td {
  padding: 1rem;
  vertical-align: middle;
  border-color: var(--gray-100);
}

.table-hover tbody tr:hover {
  background-color: rgba(37, 99, 235, 0.04);
}

/* Cards Modernas */
.metric-card, .chart-card {
  border-radius: 20px;
  box-shadow: var(--shadow-lg);
  transition: var(--transition);
  overflow: hidden;
  background: white;
}

.metric-card:hover {
  transform: translateY(-6px);
  box-shadow: var(--shadow-xl);
}

.chart-wrapper {
  position: relative;
  height: 400px;
  border-radius: 16px;
  overflow: hidden;
  background: linear-gradient(145deg, #ffffff, #f8fafc);
  box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
}

/* Responsive Grid */
@media (max-width: 992px) {
  .chart-wrapper { height: 350px; }
  .container-fluid { padding: 1rem !important; }
}

@media (max-width: 576px) {
  .chart-wrapper { height: 300px; }
  h1 { font-size: 1.75rem !important; line-height: 1.2 !important; }
  .btn { 
    padding: 0.625rem 1rem !important;
    font-size: 0.9rem !important; 
  }
}

/* Utilities */
.shadow-hover {
  transition: var(--transition);
}

.shadow-hover:hover {
  box-shadow: var(--shadow-xl) !important;
}

.text-gradient {
  background: linear-gradient(135deg, var(--primary), var(--primary-dark));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}

::-webkit-scrollbar-track {
  background: var(--gray-100);
  border-radius: 3px;
}

::-webkit-scrollbar-thumb {
  background: var(--primary);
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: var(--primary-dark);
}

/* Loading States */
.loading {
  position: relative;
}

.loading::after {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 40px;
  height: 40px;
  margin: -20px 0 0 -20px;
  border: 4px solid var(--gray-100);
  border-top: 4px solid var(--primary);
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>

/* Body & Background */
body {
  background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
  background-size: 400% 400%;
  animation: gradientBG 15s ease infinite;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
  min-height: 100vh;
}

@keyframes gradientBG {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

/* Cards Modernas */
.card-modern {
  border: none !important;
  border-radius: 20px !important;
  background: var(--glass-bg) !important;
  backdrop-filter: blur(20px) !important;
  box-shadow: var(--shadow-soft) !important;
  transition: var(--transition) !important;
  overflow: hidden;
}

.card-modern:hover {
  transform: translateY(-8px) !important;
  box-shadow: var(--shadow-hover) !important;
}

/* Icon Backgrounds */
.icon-bg, .metric-icon {
  box-shadow: 0 8px 25px rgba(0,0,0,0.15);
  transition: var(--transition);
}

.icon-bg:hover {
  transform: scale(1.1) !important;
}

/* Tables Responsive */
.table-responsive {
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
  margin-bottom: 1rem;
}

.table {
  margin-bottom: 0 !important;
  background: rgba(255,255,255,0.9);
}

.table th {
  background: rgba(59,130,246,0.1) !important;
  font-weight: 600 !important;
  border: none !important;
  color: #1f2937 !important;
  padding: 1.25rem !important;
}

.table-hover tbody tr:hover {
  background-color: rgba(59,130,246,0.08) !important;
  transform: scale(1.01);
  transition: var(--transition);
}

/* Buttons Modern */
.btn {
  border-radius: 12px !important;
  font-weight: 600 !important;
  padding: 0.75rem 1.5rem !important;
  transition: var(--transition) !important;
  border: none !important;
  position: relative;
  overflow: hidden;
}

.btn:hover {
  transform: translateY(-2px) !important;
  box-shadow: var(--shadow-hover) !important;
}

.btn-primary {
  background: var(--primary-gradient) !important;
  box-shadow: 0 4px 15px rgba(102,126,234,0.4);
}

.btn-primary:hover {
  box-shadow: 0 8px 25px rgba(102,126,234,0.6) !important;
}

/* Forms */
.form-control {
  border-radius: 12px !important;
  border: 2px solid rgba(0,0,0,0.1) !important;
  padding: 0.875rem 1.25rem !important;
  transition: var(--transition) !important;
  background: rgba(255,255,255,0.9) !important;
  backdrop-filter: blur(10px);
}

.form-control:focus {
  border-color: #3b82f6 !important;
  box-shadow: 0 0 0 0.25rem rgba(59,130,246,0.15) !important;
  transform: translateY(-1px);
}

/* Charts Container */
.chart-wrapper {
  position: relative;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 8px 32px rgba(0,0,0,0.12);
  background: rgba(255,255,255,0.9);
}

/* Responsive */
@media (max-width: 768px) {
  .container-fluid { padding: 1rem !important; }
  .card-modern { margin-bottom: 1.5rem !important; }
  .btn { padding: 0.6rem 1.2rem !important; font-size: 0.9rem !important; }
  h1, h2 { font-size: 1.75rem !important; }
}

/* Utilities */
.hover-lift {
  transition: var(--transition) !important;
}

.hover-lift:hover {
  transform: translateY(-4px) !important;
}

/* Scrollbar Custom */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: rgba(255,255,255,0.1);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(180deg, #3b82f6, #1d4ed8);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(180deg, #1d4ed8, #1e3a8a);
}
</style>
