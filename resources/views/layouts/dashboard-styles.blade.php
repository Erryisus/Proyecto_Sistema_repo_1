<style>
/* Dashboard Ultra Modern 2024 */
:root {
--glass-bg: rgba(20,25,35,0.95);
  --glass-border: rgba(75,85,99,0.6);
  --shadow-primary: 0 25px 50px -12px rgba(0,0,0,0.25);
  --shadow-glow: 0 0 30px rgba(59,130,246,0.4);
}

.dashboard-hero {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
  border-radius: 30px;
  padding: 4rem 2rem;
  margin-bottom: 3rem;
  backdrop-filter: blur(20px);
  box-shadow: var(--shadow-primary);
}

.metric-card {
  border: 1px solid var(--glass-border);
  border-radius: 24px;
  background: var(--glass-bg);
  backdrop-filter: blur(25px);
  transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
  overflow: hidden;
  position: relative;
}

.metric-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, var(--primary-gradient));
}

.metric-card:hover {
  transform: translateY(-15px) rotateX(5deg);
  box-shadow: var(--shadow-glow), var(--shadow-primary);
}

.metric-icon {
  position: absolute;
  top: 1.5rem;
  left: 1.5rem;
  width: 80px;
  height: 80px;
  border-radius: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 15px 35px rgba(0,0,0,0.2);
  border: 3px solid rgba(255,255,255,0.3);
  backdrop-filter: blur(10px);
}

.chart-wrapper {
  border-radius: 20px;
  overflow: hidden;
  background: rgba(255,255,255,0.05);
  box-shadow: inset 0 2px 10px rgba(0,0,0,0.1);
}

.rank-item {
  transition: all 0.3s ease;
  border-radius: 16px;
  margin-bottom: 0.5rem;
  padding: 1.25rem;
  border-left: 5px solid transparent;
}

.rank-item:hover {
  transform: translateX(8px);
  border-left-color: #3b82f6;
  background: rgba(59,130,246,0.05);
}

.rank-number {
  font-size: 2rem;
  font-weight: 900;
  background: linear-gradient(135deg, #3b82f6, #1d4ed8);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* Mobile Perfection */
@media (max-width: 768px) {
  .metric-card { margin-bottom: 1.5rem; }
  .metric-icon { width: 70px; height: 70px; left: 1.25rem; top: 1.25rem; }
  .chart-wrapper { height: 320px !important; }
}

@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}

.metric-card:hover .metric-icon {
  animation: float 2s ease-in-out infinite;
}
</style>

