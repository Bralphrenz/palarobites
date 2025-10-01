<?php
session_start();
require 'admin/db_connect.php';

$user_firstname = 'User';
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $result = $conn->query("SELECT first_name FROM user_info WHERE user_id = $uid LIMIT 1");
    if ($result && $row = $result->fetch_assoc()) {
        $user_firstname = htmlspecialchars($row['first_name']);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Programmers Guild Ordering System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    :root {
      --color-primary: #1a1a1a;
      --color-secondary: #d4af37;
      --color-accent: #8b4513;
      --color-light: #f8f5f0;
      --color-dark: #2a2a2a;
      --color-muted: #6b6b6b;
    }
    
    body {
      font-family: 'Montserrat', sans-serif;
      background: var(--color-light);
      min-height: 100vh;
      color: var(--color-primary);
    }

    h1, h2, h3, h4, h5, h6 {
      font-family: 'Cormorant Garamond', serif;
      font-weight: 600;
      letter-spacing: 0.5px;
    }

    #categoryModal {
      z-index: 50;
      background: rgba(26, 26, 26, 0.85);
      backdrop-filter: blur(8px);
      transition: opacity 0.4s ease;
      display: none;
      justify-content: center;
      align-items: center;
    }

    #categoryModal .modal-content {
      background: #ffffff;
      border: none;
      animation: modalSlide 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
      border-radius: 12px;
      overflow: hidden;
    }

    @keyframes modalSlide {
      from {
        opacity: 0;
        transform: translateY(-30px) scale(0.95);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    .category-card {
      background: #ffffff;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
      border-radius: 8px;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .category-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(139, 69, 19, 0.1) 100%);
      opacity: 0;
      transition: opacity 0.4s ease;
      z-index: 1;
    }

    .category-card:hover::before {
      opacity: 1;
    }

    .category-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
    }

    .category-card .image-wrapper {
      position: relative;
      overflow: hidden;
      height: 280px;
    }

    .category-card img {
      transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .category-card:hover img {
      transform: scale(1.1);
    }

    .category-card > div {
      position: relative;
      z-index: 2;
    }

    .category-card .badge {
      position: absolute;
      top: 1rem;
      right: 1rem;
      background: var(--color-secondary);
      color: var(--color-primary);
      font-size: 0.75rem;
      font-weight: 600;
      padding: 0.5rem 1rem;
      border-radius: 50px;
      z-index: 3;
      font-family: 'Montserrat', sans-serif;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
      letter-spacing: 0.5px;
    }

    #categoryModal .swiper-container {
      padding: 2rem 0 4rem 0;
    }

    #categoryModal .swiper-slide {
      background: #ffffff;
      padding: 2rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      transition: all 0.3s ease;
      border-radius: 8px;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    #categoryModal .swiper-slide:hover {
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
      transform: translateY(-4px);
    }

    #categoryModal .swiper-slide img {
      margin-bottom: 1.5rem;
      max-height: 220px;
      width: 100%;
      object-fit: cover;
      background: var(--color-light);
      padding: 1rem;
      border-radius: 8px;
      transition: transform 0.3s ease;
    }

    #categoryModal .swiper-slide:hover img {
      transform: scale(1.05);
    }

    #categoryModal .product-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--color-primary);
      margin-bottom: 0.75rem;
      text-align: center;
      font-family: 'Cormorant Garamond', serif;
    }

    #categoryModal .product-desc {
      font-size: 0.875rem;
      color: var(--color-muted);
      margin-bottom: 1.25rem;
      text-align: center;
      min-height: 2.5rem;
      line-height: 1.6;
    }

    #categoryModal .product-price {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--color-secondary);
      margin-bottom: 1.25rem;
      text-align: center;
      font-family: 'Cormorant Garamond', serif;
    }

    .btn-main {
      background: var(--color-primary);
      color: #ffffff;
      font-weight: 500;
      border: 2px solid var(--color-primary);
      transition: all 0.3s ease;
      font-family: 'Montserrat', sans-serif;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      font-size: 0.875rem;
    }

    .btn-main:hover {
      background: var(--color-secondary);
      border-color: var(--color-secondary);
      color: var(--color-primary);
      transform: translateY(-2px);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
    }

    .btn-outline {
      border: 2px solid var(--color-primary);
      color: var(--color-primary);
      background: transparent;
      font-weight: 500;
      transition: all 0.3s ease;
      font-family: 'Montserrat', sans-serif;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      font-size: 0.875rem;
    }

    .btn-outline:hover {
      background: var(--color-primary);
      color: #ffffff;
      transform: translateY(-2px);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
    }

    #categoryModal .swiper-button-next,
    #categoryModal .swiper-button-prev {
      color: var(--color-secondary);
      width: 3rem;
      height: 3rem;
      border: 2px solid var(--color-secondary);
      background: #ffffff;
      transition: all 0.3s ease;
      border-radius: 50%;
    }

    #categoryModal .swiper-button-next:hover,
    #categoryModal .swiper-button-prev:hover {
      background: var(--color-secondary);
      color: var(--color-primary);
      transform: scale(1.1);
    }

    #categoryModal .swiper-button-next::after,
    #categoryModal .swiper-button-prev::after {
      font-size: 1.25rem;
      font-weight: bold;
    }

    #categoryModal .swiper-pagination-bullet {
      background: var(--color-muted);
      opacity: 0.5;
      width: 10px;
      height: 10px;
    }

    #categoryModal .swiper-pagination-bullet-active {
      background: var(--color-secondary);
      opacity: 1;
      width: 30px;
      border-radius: 5px;
    }

    .hero-section {
      position: relative;
      height: 600px;
      overflow: hidden;
    }

    .hero-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, rgba(26, 26, 26, 0.7) 0%, rgba(139, 69, 19, 0.5) 100%);
      z-index: 1;
    }

    .hero-section img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 8s ease-out;
    }

    .hero-section:hover img {
      transform: scale(1.05);
    }

    .premium-header {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .fade-in {
      opacity: 0;
      transition: opacity 0.4s ease;
    }

    .fade-in.show {
      opacity: 1;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    .animate-spin {
      display: inline-block;
      animation: spin 1s linear infinite;
    }

    .section-divider {
      height: 2px;
      background: linear-gradient(90deg, transparent 0%, var(--color-secondary) 50%, transparent 100%);
      margin: 3rem 0;
    }

    /* <CHANGE> Updated chat button styling */
    #chatButton {
      position: fixed;
      bottom: 1.5rem;
      right: 1.5rem;
      width: 3.5rem;
      height: 3.5rem;
      background: var(--color-primary);
      color: #ffffff;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
      z-index: 40;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      border: 2px solid var(--color-secondary);
      font-size: 1.5rem;
    }

    #chatButton:hover {
      background: var(--color-secondary);
      color: var(--color-primary);
      transform: scale(1.1);
      box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.4);
    }

    /* <CHANGE> Redesigned chat modal to match premium aesthetic */
    #chatModal {
      position: fixed;
      bottom: 6rem;
      right: 1.5rem;
      width: 24rem;
      max-width: calc(100vw - 3rem);
      max-height: 70vh;
      background: #ffffff;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
      border-radius: 12px;
      border: 2px solid var(--color-secondary);
      display: flex;
      flex-direction: column;
      z-index: 40;
      overflow: hidden;
      animation: chatSlide 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes chatSlide {
      from {
        opacity: 0;
        transform: translateY(20px) scale(0.95);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    #chatModal.hidden {
      display: none;
    }

    /* <CHANGE> Updated chat header styling */
    #chatModal .chat-header {
      background: var(--color-primary);
      color: #ffffff;
      padding: 1rem 1.25rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 2px solid var(--color-secondary);
    }

    #chatModal .chat-header h2 {
      font-size: 1.25rem;
      font-weight: 600;
      font-family: 'Cormorant Garamond', serif;
      letter-spacing: 0.5px;
    }

    #chatModal .chat-header button {
      color: #ffffff;
      font-size: 1.75rem;
      line-height: 1;
      transition: all 0.3s ease;
      background: transparent;
      border: none;
      cursor: pointer;
      width: 2rem;
      height: 2rem;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
    }

    #chatModal .chat-header button:hover {
      background: var(--color-secondary);
      color: var(--color-primary);
      transform: rotate(90deg);
    }

    /* <CHANGE> Improved chat messages area styling */
    #chatMessages {
      flex: 1;
      overflow-y: auto;
      padding: 1.25rem;
      background: var(--color-light);
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
    }

    #chatMessages::-webkit-scrollbar {
      width: 6px;
    }

    #chatMessages::-webkit-scrollbar-track {
      background: transparent;
    }

    #chatMessages::-webkit-scrollbar-thumb {
      background: var(--color-muted);
      border-radius: 3px;
    }

    #chatMessages::-webkit-scrollbar-thumb:hover {
      background: var(--color-secondary);
    }

    #chatMessages .empty-state {
      color: var(--color-muted);
      text-align: center;
      font-size: 0.875rem;
      margin: auto;
      font-family: 'Montserrat', sans-serif;
    }

    /* <CHANGE> Enhanced message bubble styling */
    #chatMessages .message-bubble {
      display: inline-block;
      padding: 0.75rem 1rem;
      border-radius: 12px;
      margin-bottom: 0.5rem;
      max-width: 80%;
      word-wrap: break-word;
      font-size: 0.95rem;
      line-height: 1.5;
      font-family: 'Montserrat', sans-serif;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    }

    #chatMessages .justify-end .message-bubble {
      background: var(--color-primary);
      color: #fff;
      border-bottom-right-radius: 4px;
    }

    #chatMessages .justify-start .message-bubble {
      background: #fff;
      color: var(--color-primary);
      border: 1.5px solid var(--color-secondary);
      border-bottom-left-radius: 4px;
    }

    /* <CHANGE> Refined chat input area */
    #chatModal .chat-input-area {
      padding: 1rem;
      border-top: 2px solid var(--color-secondary);
      background: #ffffff;
      display: flex;
      gap: 0.75rem;
    }

    #chatModal .chat-input-area input {
      flex: 1;
      border: 2px solid var(--color-primary);
      border-radius: 8px;
      padding: 0.75rem 1rem;
      font-size: 0.875rem;
      font-family: 'Montserrat', sans-serif;
      outline: none;
      transition: all 0.3s ease;
      background: var(--color-light);
      color: var(--color-primary);
    }

    #chatModal .chat-input-area input:focus {
      border-color: var(--color-secondary);
      background: #ffffff;
      box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
    }

    #chatModal .chat-input-area input::placeholder {
      color: var(--color-muted);
    }

    #chatModal .chat-input-area button {
      background: var(--color-primary);
      color: #ffffff;
      padding: 0.75rem 1.5rem;
      border-radius: 8px;
      border: 2px solid var(--color-primary);
      font-weight: 500;
      font-size: 0.875rem;
      font-family: 'Montserrat', sans-serif;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      cursor: pointer;
      transition: all 0.3s ease;
      white-space: nowrap;
    }

    #chatModal .chat-input-area button:hover {
      background: var(--color-secondary);
      border-color: var(--color-secondary);
      color: var(--color-primary);
      transform: translateY(-2px);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
    }

    @media (max-width: 768px) {
      .hero-section {
        height: 400px;
      }
      
      .category-card .image-wrapper {
        height: 220px;
      }
      
      #categoryModal .product-title {
        font-size: 1.25rem;
      }

      #chatButton {
        width: 3rem;
        height: 3rem;
        font-size: 1.25rem;
        bottom: 1rem;
        right: 1rem;
      }

      #chatModal {
        width: calc(100vw - 2rem);
        right: 1rem;
        bottom: 5rem;
      }
    }
  </style>
</head>
<body>
  <section class="hero-section">
    <img src="assets/img/Main.jpg" alt="Delicious Dish">
    
    <header class="premium-header absolute top-0 left-0 w-full z-20 p-4 sm:p-6">
      <div class="container mx-auto flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="text-3xl sm:text-4xl md:text-5xl font-bold" style="font-family: 'Cormorant Garamond', serif; color: var(--color-primary);">
          Programmers Guild
        </div>
        
        <div class="flex items-center gap-4 sm:gap-6">
          <a href="cart_list.php" class="relative group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 sm:h-8 sm:w-8 transition-all duration-300 group-hover:scale-110" style="color: var(--color-primary);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.6 8M17 13l1.6 8M9 21h6" />
            </svg>
            <span id="cartCount" class="absolute -top-2 -right-2 text-xs font-bold rounded-full px-2 py-1 border-2" style="background: var(--color-secondary); color: var(--color-primary); border-color: var(--color-secondary);">0</span>
          </a>
          
          <?php if (isset($_SESSION['user_id'])): ?>
            <div class="relative group">
              <button id="userDropdownBtn" type="button" class="flex items-center gap-2 font-medium px-4 sm:px-6 py-2.5 border-2 transition-all duration-300 hover:scale-105" style="color: var(--color-primary); border-color: var(--color-primary);">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <circle cx="12" cy="12" r="10"/>
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                <span class="hidden sm:inline">Hi, <?php echo htmlspecialchars($user_firstname); ?></span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <div id="userDropdownMenu" class="absolute right-0 mt-2 w-56 bg-white border-2 py-2 z-30 hidden shadow-xl rounded-lg" style="border-color: var(--color-primary);">
                <div class="px-4 py-3 border-b text-sm" style="border-color: var(--color-secondary); color: var(--color-muted);">
                  <span class="block font-medium">Signed in as</span>
                  <span class="block truncate font-semibold mt-1" style="color: var(--color-primary);"><?php echo htmlspecialchars($user_firstname); ?></span>
                </div>
                <a href="profile.php" class="flex items-center gap-3 px-4 py-3 transition-all duration-300" style="color: var(--color-dark);" onmouseover="this.style.background='var(--color-light)'" onmouseout="this.style.background='transparent'">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                  Profile
                </a>
                <a href="vieworder.php" class="flex items-center gap-3 px-4 py-3 transition-all duration-300"
                  style="color: var(--color-dark);" 
                  onmouseover="this.style.background='var(--color-light)'" 
                  onmouseout="this.style.background='transparent'">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="9" y="2" width="6" height="4" rx="1" />
                    <rect x="4" y="6" width="16" height="16" rx="2" />
                    <path d="M9 10h6M9 14h6" />
                  </svg>
                  View Order
                </a>
                <a href="logout.php" class="flex items-center gap-3 px-4 py-3 border-t transition-all duration-300" style="color: var(--color-dark); border-color: var(--color-light);" onmouseover="this.style.background='var(--color-light)'" onmouseout="this.style.background='transparent'">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1"/>
                  </svg>
                  Logout
                </a>
              </div>
            </div>
          <?php else: ?>
            <a href="login.php" class="flex items-center gap-2 px-4 sm:px-6 py-2.5 font-medium border-2 transition-all duration-300 hover:scale-105 text-sm sm:text-base" style="background: white; color: var(--color-primary); border-color: var(--color-primary);">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
              </svg>
              <span class="hidden sm:inline">Log in</span>
            </a>
            <a href="register.php" class="flex items-center gap-2 px-4 sm:px-6 py-2.5 font-medium border-2 transition-all duration-300 hover:scale-105 text-sm sm:text-base" style="background: white; color: var(--color-primary); border-color: var(--color-primary);">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
              </svg>
              <span class="hidden sm:inline">Sign in</span>
            </a>
          <?php endif; ?>
        </div>
      </div>
    </header>

    <div class="absolute inset-0 z-10 flex items-center justify-center text-center px-4">
      <div data-aos="fade-up" data-aos-duration="1000">
        <h1 class="text-4xl sm:text-5xl md:text-7xl font-bold text-white mb-4" style="font-family: 'Cormorant Garamond', serif; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
          Exquisite Culinary Experience
        </h1>
        <p class="text-lg sm:text-xl md:text-2xl text-white mb-8" style="font-family: 'Montserrat', sans-serif; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
          Discover the finest flavors crafted with passion
        </p>
        <a href="#cuisines" class="btn-main px-8 py-3 inline-block">Explore Menu</a>
      </div>
    </div>
  </section>

  <main class="container mx-auto px-4 py-16 sm:py-24 max-w-7xl">
    <section id="cuisines" class="mb-16">
      <div class="text-center mb-12 sm:mb-16" data-aos="fade-up">
        <h2 class="text-4xl sm:text-5xl md:text-6xl font-bold mb-4" style="font-family: 'Cormorant Garamond', serif; color: var(--color-primary);">
          Foods Available
        </h2>
        <div class="section-divider"></div>
        <p class="text-lg sm:text-xl mt-6" style="color: var(--color-muted); font-family: 'Montserrat', sans-serif;">
          Handpicked selections from our premium collection
        </p>
      </div>
      
      <div id="categoryGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 sm:gap-10">
        <?php
        $categories = $conn->query("SELECT * FROM category_list");
        while ($category = $categories->fetch_assoc()):
          $cat_id = $category['id'];
          $prod_count = $conn->query("SELECT COUNT(*) FROM product_list WHERE category_id = $cat_id")->fetch_row()[0];
        ?>
        <div class="category-card" data-aos="fade-up" data-aos-delay="100">
          <div class="image-wrapper">
            <img src="assets/img/<?php echo $category['img_path']; ?>" alt="<?php echo $category['name']; ?>">
            <span class="badge"><?php echo $prod_count; ?> items</span>
          </div>
          <div class="p-6">
            <h3 class="text-2xl sm:text-3xl font-semibold mb-3" style="font-family: 'Cormorant Garamond', serif; color: var(--color-primary);">
              <?php echo $category['name']; ?>
            </h3>
            <p class="text-sm mb-6 min-h-[3rem] leading-relaxed" style="color: var(--color-muted);">
              <?php echo isset($category['description']) ? htmlspecialchars($category['description']) : 'Explore our exquisite selection of handcrafted dishes'; ?>
            </p>
            <button onclick="viewCategory(<?php echo $category['id']; ?>)" class="btn-main px-8 py-3 w-full">View Menu</button>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
    </section>
  </main>

   <CHANGE> Redesigned chat button with premium styling 
  <div id="chatButton">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
    </svg>
  </div>

   <CHANGE> Redesigned chat modal with premium aesthetic 
  <div id="chatModal" class="hidden">
    <div class="chat-header">
      <h2>Messages</h2>
      <button id="closeChat">&times;</button>
    </div>

    <div id="chatMessages">
      <div class="empty-state">Start chatting with admin...</div>
    </div>

    <div class="chat-input-area">
      <input type="text" id="chatInput" placeholder="Type a message...">
      <button id="sendMessage">Send</button>
    </div>
  </div>

  <div id="categoryModal" class="fixed inset-0 flex items-center justify-center hidden fade-in p-4">
    <div class="modal-content w-full max-w-6xl relative">
      <div class="flex justify-between items-center p-6 sm:p-8 border-b-2" style="background: var(--color-light); border-color: var(--color-secondary);">
        <h3 class="text-2xl sm:text-3xl font-bold" style="font-family: 'Cormorant Garamond', serif; color: var(--color-primary);">Menu Selection</h3>
        <button id="closeCategoryModal" class="transition-all duration-300 hover:scale-110" style="color: var(--color-muted);">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 sm:h-8 sm:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
      <div id="modalCategoryContent" class="px-6 sm:px-10 pb-8 sm:pb-12">
        <h3 class="text-3xl sm:text-4xl font-bold my-8 text-center" style="font-family: 'Cormorant Garamond', serif; color: var(--color-primary);">Available Dishes</h3>
        <div class="swiper-container">
          <div class="swiper-wrapper" id="categoryProducts">
             Products will be dynamically loaded here 
          </div>
          <div class="swiper-pagination mt-6"></div>
          <div class="swiper-button-next"></div>
          <div class="swiper-button-prev"></div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function viewCategory(categoryId) {
      const modal = document.getElementById('categoryModal');
      modal.style.display = 'flex';
      setTimeout(() => {
        modal.classList.remove('hidden');
        modal.classList.add('show');
      }, 10);
      
      $.ajax({
        url: 'admin/ajax.php?action=get_products_by_category',
        method: 'POST',
        data: { category_id: categoryId },
        success: function(response) {
          $('#categoryProducts').html(response); 
          
          setTimeout(() => {
            new Swiper('.swiper-container', {
              loop: true,
              slidesPerView: 1,
              spaceBetween: 30,
              breakpoints: {
                640: {
                  slidesPerView: 2,
                  spaceBetween: 30,
                },
                1024: {
                  slidesPerView: 3,
                  spaceBetween: 40,
                },
              },
              navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
              },
              pagination: {
                el: '.swiper-pagination',
                clickable: true,
              },
            });
          }, 100);
        },
        error: function() {
          alert('An error occurred while loading the products.');
          const modal = document.getElementById('categoryModal');
          modal.classList.add('hidden');
          modal.classList.remove('show');
        }
      });
    }

    document.getElementById('closeCategoryModal').addEventListener('click', function() {
      const modal = document.getElementById('categoryModal');
      modal.classList.remove('show');
      setTimeout(() => {
        modal.classList.add('hidden');
        modal.style.display = 'none';
      }, 400);
    });

    document.getElementById('categoryModal').addEventListener('click', function(e) {
      if (e.target === this) {
        const modal = document.getElementById('categoryModal');
        modal.classList.remove('show');
        setTimeout(() => {
          modal.classList.add('hidden');
          modal.style.display = 'none';
        }, 400);
      }
    });

    function updateCartCount() {
      $.ajax({
        url: 'admin/ajax.php?action=get_cart_count',
        method: 'GET',
        success: function (response) {
          $('#cartCount').text(response); 
        },
        error: function () {
          alert('An error occurred while updating the cart count.');
        },
      });
    }

    function addToCart(productId, btn = null) {
      let qtyInput = document.getElementById(`qty_${productId}`);
      const qty = qtyInput ? parseInt(qtyInput.value) || 1 : 1;

      if (btn) {
        btn.disabled = true;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="animate-spin mr-2">&#9696;</span>Adding...';

        $.ajax({
          url: 'admin/ajax.php?action=add_to_cart',
          method: 'POST',
          data: { pid: productId, qty: qty },
          success: function (response) {
            const res = JSON.parse(response);
            if (res.status === 1) {
              btn.innerHTML = '<span class="text-black">&#10003;</span> Added!';
              updateCartCount();
              setTimeout(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
              }, 1200);
            } else {
              btn.innerHTML = originalText;
              btn.disabled = false;
              alert(res.message);
              window.location.href = 'login.php';
            }
          },
          error: function () {
            btn.innerHTML = originalText;
            btn.disabled = false;
            alert('An error occurred while adding the product to the cart.');
          },
        });
      } else {
        $.ajax({
          url: 'admin/ajax.php?action=add_to_cart',
          method: 'POST',
          data: { pid: productId, qty: qty },
          success: function (response) {
            const res = JSON.parse(response);
            if (res.status === 1) {
              alert(res.message);
              updateCartCount();
            } else {
              alert(res.message);
              window.location.href = 'login.php';
            }
          },
          error: function () {
            alert('An error occurred while adding the product to the cart.');
          },
        });
      }
    }

    $(document).ready(function () {
      updateCartCount();
      AOS.init({
        duration: 800,
        once: true,
        offset: 100
      });
    });
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const btn = document.getElementById('userDropdownBtn');
      const menu = document.getElementById('userDropdownMenu');
      if(btn && menu) {
        btn.addEventListener('click', function(e) {
          e.stopPropagation();
          menu.classList.toggle('hidden');
        });
        document.addEventListener('click', function() {
          menu.classList.add('hidden');
        });
      }
    });

    // <CHANGE> Updated chat functionality with improved message styling
    $(document).ready(function () {
      $("#chatButton").click(() => $("#chatModal").toggleClass("hidden"));
      $("#closeChat").click(() => $("#chatModal").addClass("hidden"));

      $("#sendMessage").click(function () {
        let msg = $("#chatInput").val().trim();
        if (msg === "") return;

        $.post("admin/ajax.php?action=send_message", { message: msg }, function (res) {
          if (res.success) {
            $("#chatMessages").append(
              `<div class="message-right"><div class="message-bubble">${msg}</div></div>`
            );
            $("#chatInput").val("");
            $("#chatMessages").scrollTop($("#chatMessages")[0].scrollHeight);
          } else {
            alert(res.error || "Error sending message.");
          }
        }, "json");
      });

      // Allow Enter key to send message
      $("#chatInput").keypress(function(e) {
        if (e.which === 13) {
          $("#sendMessage").click();
        }
      });

      function fetchMessages() {
        $.get("admin/ajax.php?action=fetch_messages", function (res) {
          if (res.success) {
            $("#chatMessages").html("");
            if (res.messages.length === 0) {
              $("#chatMessages").html('<div class="empty-state">Start chatting with admin...</div>');
            } else {
              res.messages.forEach(m => {
                // Use your session user_id for comparison
                let isMine = (m.sender_id == <?php echo json_encode($_SESSION['user_id'] ?? 0); ?>);
                let side = isMine ? "justify-end" : "justify-start";
                let bubble = isMine
                  ? "bg-yellow-500 text-white"
                  : "bg-white text-black border border-yellow-400";
                let align = isMine ? "message-right" : "message-left";
                $("#chatMessages").append(
                  `<div class="flex ${side}">
                    <div class="message-bubble ${bubble} ${align}">${escapeHtml(m.message)}</div>
                  </div>`
                );
              });
            }
            $("#chatMessages").scrollTop($("#chatMessages")[0].scrollHeight);
          }
        }, "json");
      }

      // Helper to escape HTML
      function escapeHtml(text) {
        return $('<div>').text(text).html();
      }

      setInterval(fetchMessages, 5000);
      fetchMessages();
    });
  </script>
</body>
</html>