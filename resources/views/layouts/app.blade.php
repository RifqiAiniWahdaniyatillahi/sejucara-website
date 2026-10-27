<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sejucara - Teh Cascara' }}</title>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-N90KD5Q47Y"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-N90KD5Q47Y');
</script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bagel+Fat+One&family=Nunito:wght@400;500;600;700;800&display=swap');

        :root {
            --bg: #f4ecdd;
            --surface: #fffaf2;
            --text: #3b2a1f;
            --muted: #6f5b4b;
            --primary: #c96a31;
            --primary-dark: #a84f1f;
            --brand-text: #d06a34;
            --danger: #b63a3a;
            --border: #e9cfaa;
            --heading: #8b3a0f;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "Nunito", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text);
            background: linear-gradient(180deg, #faf4e8 0%, var(--bg) 45%, #f2e8d4 100%);
        }

        h1, h2, h3, h4 {
            font-family: "Bagel Fat One", "Nunito", sans-serif;
            color: var(--heading);
            letter-spacing: 0.3px;
            font-weight: 400;
        }

        .container {
            width: min(1200px, 92%);
            margin: 0 auto;
        }

        .navbar {
            background: var(--surface);
            border-bottom: 2px solid #ecd9bc;
            position: sticky;
            top: 0;
            z-index: 20;
        }

        .navbar-inner {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 12px 0;
        }

        .navbar-top {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-wrap {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            flex-shrink: 0;
        }

        .brand-mark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand-mark-img {
            width: 42px;
            height: 42px;
            border-radius: 999px;
            object-fit: cover;
            display: block;
        }

        .brand {
            font-family: "Bagel Fat One", "Nunito", sans-serif;
            font-size: 32px;
            font-weight: 400;
            line-height: 1;
            letter-spacing: 0.2px;
            color: var(--brand-text);
        }

        .mobile-menu-toggle {
            display: none;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border);
            border-radius: 999px;
            background: #fff;
            color: var(--primary-dark);
            width: 42px;
            height: 42px;
            padding: 0;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(90, 44, 20, 0.08);
        }

        .mobile-menu-toggle svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .mobile-cart-shortcut,
        .nav-chat-shortcut {
            display: inline-flex;
        }

        .icon-nav-link.mobile-notification-shortcut {
            display: none;
        }

        .mobile-nav-overlay {
            display: none;
        }

        .navbar-bottom {
            width: 100%;
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            gap: 12px;
        }

        .navbar-left-spacer {
            min-height: 1px;
        }

        .nav-search {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid var(--border);
            border-radius: 999px;
            background: #fff;
            padding: 4px 6px 4px 12px;
        }

        .navbar-search-row {
            min-width: 0;
            flex: 1;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-search input {
            min-width: 0;
            flex: 1;
            border: none;
            padding: 7px 4px;
            font-size: 14px;
            outline: none;
            background: transparent;
        }

        .nav-search button {
            border: 1px solid var(--primary);
            background: var(--primary);
            color: #fff;
            border-radius: 999px;
            padding: 6px 12px;
            font-size: 12px;
            cursor: pointer;
        }

        .menu {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .navbar-tools {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .menu a, .btn-link {
            text-decoration: none;
            color: var(--text);
            padding: 8px 10px;
            border-radius: 8px;
            border: none;
            background: transparent;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
        }

        .menu a.is-active {
            color: var(--heading);
            background: #ffe8cb;
        }

        .menu a:hover, .btn-link:hover {
            background: #fff2de;
        }

        .navbar-tools > a:not(.icon-nav-link),
        .navbar-tools .btn-link {
            border: none;
            border-radius: 8px;
            padding: 8px 10px;
            font-size: 14px;
            font-weight: 700;
            color: #5e3419;
            text-decoration: none;
            background: transparent;
        }

        .navbar-tools > a:not(.icon-nav-link):hover,
        .navbar-tools .btn-link:hover {
            background: #fff2de;
        }

        .icon-nav-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            color: #5e3419;
            text-decoration: none;
            background: transparent;
            position: relative;
        }

        .icon-nav-link:hover {
            background: #fff2de;
        }

        .chat-nav-link,
        .nav-chat-shortcut,
        .mobile-cart-shortcut {
            width: 44px;
            height: 44px;
            flex: 0 0 44px;
            overflow: visible;
            border: 1px solid var(--border);
            border-radius: 50%;
            background: #fff;
            color: var(--primary-dark);
            box-shadow: 0 4px 12px rgba(90, 44, 20, 0.08);
        }

        .chat-nav-link:hover,
        .nav-chat-shortcut:hover,
        .mobile-cart-shortcut:hover {
            border-color: var(--primary);
            background: #fff2de;
        }

        .chat-nav-icon {
            width: 24px;
            height: 24px;
            display: block;
            flex-shrink: 0;
            overflow: visible;
            stroke: currentColor;
            stroke-width: 1.9;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .chat-nav-link .cart-badge,
        .nav-chat-shortcut .cart-badge,
        .mobile-cart-shortcut .cart-badge {
            top: -4px;
            right: -4px;
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            min-width: 19px;
            height: 19px;
            padding: 0 5px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--danger);
            color: #fff;
            border: 2px solid var(--surface);
            font-size: 11px;
            font-weight: 900;
            line-height: 1;
        }

        .cart-icon {
            width: 21px;
            height: 21px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
            border: 1px solid var(--primary);
            border-radius: 14px;
            padding: 11px 18px;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
            font-family: "Nunito", "Segoe UI", sans-serif;
            font-weight: 800;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-danger {
            background: var(--danger);
            color: #fff;
            border: 1px solid var(--danger);
            border-radius: 8px;
            padding: 8px 12px;
            cursor: pointer;
        }

        main { padding: 34px 0 44px; }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 10px 28px rgba(90, 44, 20, 0.08);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 14px;
        }

        .product-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
            background: #f6eee7;
            border: 1px solid var(--border);
        }

        .flash {
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 14px;
            font-size: 14px;
        }

        .flash-success {
            background: #e9f8eb;
            border: 1px solid #bce5c3;
            color: #245b2d;
        }

        .flash-error {
            background: #fdecec;
            border: 1px solid #f2c0c0;
            color: #7a2222;
        }

        .field { margin-bottom: 12px; }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            font-weight: 600;
        }

        input, textarea, select {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px;
            font-size: 14px;
            background: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th, td {
            border-bottom: 1px solid var(--border);
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }

        .muted { color: var(--muted); font-size: 14px; }

        .actions {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .order-action-stack {
            display: inline-flex;
            flex-direction: column;
            align-items: stretch;
            gap: 8px;
            min-width: 120px;
        }

        .order-action-stack .btn-primary,
        .order-action-stack .btn-danger,
        .cancel-order-summary {
            width: 100%;
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            border-radius: 14px;
            font-weight: 800;
        }

        .cancel-order-details {
            width: 100%;
        }

        .cancel-order-summary {
            list-style: none;
            background: var(--danger);
            color: #fff;
            border: 1px solid var(--danger);
            padding: 10px 14px;
            cursor: pointer;
        }

        .cancel-order-summary::-webkit-details-marker {
            display: none;
        }

        .cancel-order-panel {
            width: min(280px, 76vw);
            margin-top: 8px;
            padding: 12px;
            border: 1px solid #efc2b8;
            border-radius: 14px;
            background: #fff8f5;
            box-shadow: 0 8px 20px rgba(90, 44, 20, 0.08);
        }

        .cancel-order-panel label {
            font-size: 13px;
        }

        .cancel-order-panel select {
            margin-bottom: 10px;
        }

        .cancel-reason-note {
            margin: 8px 0 0;
            padding: 10px 12px;
            border-radius: 12px;
            background: #fff4ec;
            color: var(--muted);
            font-size: 14px;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 999px;
            font-size: 12px;
            background: #f4e8dc;
            color: #6e4a32;
        }

        nav[role="navigation"][aria-label="Pagination Navigation"] {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 16px;
            font-size: 14px;
        }

        nav[role="navigation"][aria-label="Pagination Navigation"] > div:first-child {
            display: none;
        }

        nav[role="navigation"][aria-label="Pagination Navigation"] > div:last-child {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        nav[role="navigation"][aria-label="Pagination Navigation"] p {
            margin: 0;
            color: var(--muted);
        }

        nav[role="navigation"][aria-label="Pagination Navigation"] a,
        nav[role="navigation"][aria-label="Pagination Navigation"] span[aria-current] > span,
        nav[role="navigation"][aria-label="Pagination Navigation"] span[aria-disabled] > span {
            min-width: 36px;
            min-height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 11px;
            border: 1px solid var(--border);
            background: #fffaf2;
            color: var(--text);
            text-decoration: none;
            line-height: 1;
        }

        nav[role="navigation"][aria-label="Pagination Navigation"] a:hover {
            background: #fff2de;
            color: var(--heading);
        }

        nav[role="navigation"][aria-label="Pagination Navigation"] span[aria-current] > span {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        nav[role="navigation"][aria-label="Pagination Navigation"] span[aria-disabled] > span {
            color: #b59b82;
            background: #f7eddf;
        }

        nav[role="navigation"][aria-label="Pagination Navigation"] svg {
            width: 18px;
            height: 18px;
            display: block;
        }

        .home-hero,
        .home-why,
        .home-story,
        .home-products {
            margin-bottom: 20px;
        }

        .home-hero {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            align-items: center;
            background: #ffffff;
            padding: 24px;
            min-height: auto;
        }

        .home-hero-media,
        .home-story-media {
            min-height: 220px;
            border-radius: 18px;
            border: 1px solid var(--border);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .home-hero-media {
            height: clamp(280px, 45vh, 360px);
            min-height: 280px;
            box-shadow: 0 12px 30px rgba(110, 54, 17, 0.12);
        }

        .home-hero-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .home-story-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .home-story-media {
            height: clamp(280px, 34vw, 460px);
            min-height: 280px;
            box-shadow: 0 12px 30px rgba(110, 54, 17, 0.12);
        }

        .wireframe-icon {
            width: 64px;
            height: 64px;
            border: 3px solid #3f3b36;
            border-radius: 10px;
            position: relative;
        }

        .wireframe-icon::before,
        .wireframe-icon::after {
            content: "";
            position: absolute;
            background: #3f3b36;
        }

        .wireframe-icon::before {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            top: 10px;
            left: 10px;
        }

        .wireframe-icon::after {
            width: 36px;
            height: 3px;
            bottom: 14px;
            left: 13px;
            transform: rotate(-30deg);
            transform-origin: left center;
        }

        .home-hero-content h1 {
            margin: 0 0 8px;
            font-size: clamp(30px, 4.2vw, 46px);
            line-height: 1.2;
            max-width: 500px;
        }

        .home-hero-content .muted {
            max-width: 520px;
            font-size: 16px;
            line-height: 1.7;
            color: #403830;
        }

        .home-hero .actions {
            margin-top: 20px;
            gap: 12px;
        }

        .accent-word {
            color: var(--primary);
        }

        .home-why h2,
        .home-story h2,
        .home-products h2 {
            margin-top: 0;
            text-align: center;
        }

        .home-why-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .home-feature-box {
            background: #fff4e1;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 14px;
            min-height: 118px;
        }

        .home-feature-box h3 {
            margin-top: 0;
            margin-bottom: 8px;
            font-size: 17px;
        }

        .home-story-grid {
            display: grid;
            grid-template-columns: 1fr 1.15fr;
            gap: 18px;
            align-items: stretch;
        }

        .home-story-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .home-story-cta {
            margin-top: 8px;
            align-self: flex-start;
        }

        .home-products-grid {
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        }

        .home-articles-grid {
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        }

        .home-product-card h3 {
            margin-bottom: 6px;
        }

        .home-article-card {
            padding: 0;
            overflow: hidden;
        }

        .home-article-card .article-image-wrapper {
            height: 180px;
            border-bottom: 1px solid var(--border);
            background: #fff;
            overflow: hidden;
        }

        .home-article-card .article-image {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            object-position: center;
        }

        .home-article-card .article-info {
            padding: 14px;
        }

        .home-article-card .article-title {
            margin-bottom: 8px;
            font-size: 16px;
            line-height: 1.35;
        }

        .home-article-card .article-excerpt {
            margin-bottom: 10px;
            font-size: 14px;
        }

        .home-article-card .article-footer {
            margin-top: 12px;
        }

        .home-product-card .product-image {
            object-fit: contain;
            object-position: center;
            background: #ffffff;
            padding: 10px;
        }

        .about-banner,
        .about-hero,
        .about-section,
        .about-highlights {
            margin-bottom: 16px;
        }

        .about-banner {
            position: relative;
            overflow: hidden;
            padding: 0;
            min-height: 250px;
            border-radius: 18px;
        }

        .about-banner-photo {
            width: 100%;
            height: 100%;
            min-height: 250px;
            object-fit: cover;
            display: block;
        }

        .about-banner-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            gap: 10px;
            padding: 18px;
            background: linear-gradient(180deg, rgba(35, 21, 10, 0.08) 20%, rgba(35, 21, 10, 0.68) 100%);
        }

        .about-banner-tag {
            display: inline-flex;
            align-self: flex-start;
            width: fit-content;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: 0.2px;
            color: #6a3616;
            background: rgba(255, 245, 230, 0.95);
            border: 1px solid rgba(255, 234, 203, 0.9);
        }

        .about-banner-title {
            margin: 0;
            max-width: 620px;
            color: #fff7ea;
            font-size: clamp(24px, 3.2vw, 38px);
            line-height: 1.18;
            letter-spacing: 0;
            font-family: "Nunito", "Segoe UI", sans-serif;
            font-weight: 900;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
        }

        .about-hero {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 20px;
            align-items: center;
            background: linear-gradient(180deg, #fffdfa 0%, #fff5e9 100%);
        }

        .about-hero-content {
            max-width: 600px;
        }

        .about-hero-content h1 {
            margin: 10px 0 10px;
            font-size: clamp(26px, 3.2vw, 38px);
            line-height: 1.22;
            max-width: 560px;
        }

        .about-lead {
            margin-top: 0;
            margin-bottom: 12px;
            font-size: 16px;
            line-height: 1.7;
            color: #3e3328;
            max-width: 560px;
        }

        .about-hero-content .muted {
            line-height: 1.68;
            max-width: 560px;
        }

        .about-link {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 10px 16px;
            background: #fff;
        }

        .about-hero-media {
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            width: min(100%, 420px);
            justify-self: end;
            height: 320px;
            min-height: 320px;
            box-shadow: 0 12px 30px rgba(110, 54, 17, 0.12);
        }

        .about-hero-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .about-highlights {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }

        .about-highlight {
            position: relative;
            overflow: hidden;
            background: linear-gradient(180deg, #fffaf2 0%, #fff4e5 100%);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 16px;
            box-shadow: 0 10px 22px rgba(90, 44, 20, 0.07);
        }

        .about-highlight::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #d07a3e 0%, #f0c080 100%);
        }

        .about-highlight::after {
            content: "";
            position: absolute;
            right: -18px;
            bottom: -22px;
            width: 72px;
            height: 72px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(208, 122, 62, 0.12) 0%, rgba(208, 122, 62, 0) 75%);
            pointer-events: none;
        }

        .about-highlight h3 {
            margin: 0 0 9px;
            font-size: 19px;
        }

        .about-highlight .muted {
            margin: 0;
            line-height: 1.72;
            color: #5f4c3d;
        }

        .about-section h2 {
            margin-top: 0;
            margin-bottom: 14px;
            text-align: center;
        }

        .about-split {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .about-panel {
            background: #fffaf2;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 8px 20px rgba(90, 44, 20, 0.06);
        }

        .about-panel-title {
            margin: 0 0 10px;
            display: block;
            text-align: center;
            font-size: 22px;
        }

        .about-panel p {
            margin: 0;
            line-height: 1.75;
        }

        .about-mission-list {
            margin: 0;
            padding-left: 20px;
            line-height: 1.78;
            color: #4f3f32;
        }

        .about-mission-list li {
            margin-bottom: 6px;
        }

        .about-benefits-section {
            width: 100vw;
            margin: 16px calc(50% - 50vw) 0;
            padding: 42px 0;
            background: linear-gradient(180deg, #f6efdf 0%, #efe3cf 100%);
            border-top: 1px solid #e4cfad;
            border-bottom: 1px solid #e4cfad;
        }

        .about-benefits-section h2 {
            margin-bottom: 14px;
        }

        .about-benefits-copy {
            margin: 0 auto;
            max-width: 940px;
            padding: 0 22px;
            text-align: center;
            color: #5c4b3d;
            line-height: 1.85;
            font-size: 17px;
        }

        .site-footer {
            margin-top: 0;
            background: #8b3707;
            color: #ffdba4;
        }

        .site-footer-inner {
            display: grid;
            grid-template-columns: 1.25fr 0.9fr 1.15fr;
            gap: 26px;
            padding: 46px 0 38px;
            align-items: start;
        }

        .site-footer h4 {
            margin: 0 0 14px;
            color: #fff1cb;
            font-size: 22px;
            line-height: 1;
            font-family: "Bagel Fat One", "Nunito", sans-serif;
        }

        .site-footer p {
            margin: 0;
            color: #ffdba4;
            font-size: 17px;
            line-height: 1.65;
        }

        .site-footer-links {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .site-footer-links a {
            text-decoration: none;
            color: #ffdba4;
            font-size: 17px;
            line-height: 1.35;
            transition: color 0.18s ease;
        }

        .site-footer-links a:hover {
            color: #fff4d6;
            text-decoration: underline;
        }

        .site-footer-brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
        }

        .site-footer-brand .brand-mark {
            width: 52px;
            height: 52px;
        }

        .site-footer-brand .brand-mark-img {
            width: 52px;
            height: 52px;
        }

        .site-footer-brand .brand {
            font-size: 40px;
            color: #fff1cb;
        }

        .site-footer-note {
            max-width: 420px;
        }

        .site-footer-contact {
            display: grid;
            grid-template-columns: repeat(5, 42px);
            gap: 8px;
            align-items: center;
        }

        .site-footer-contact a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            color: #ffdba4;
            width: 42px;
            height: 42px;
            border: 1px solid #b95a20;
            border-radius: 999px;
            background: rgba(255, 244, 214, 0.06);
            transition: color 0.18s ease, background 0.18s ease, transform 0.18s ease;
        }

        .site-footer-contact a:hover {
            color: #fff4d6;
            background: rgba(255, 244, 214, 0.14);
            transform: translateY(-1px);
        }

        .footer-icon {
            width: 18px;
            height: 18px;
            fill: currentColor;
            opacity: 0.92;
            flex-shrink: 0;
        }

        .site-footer-contact-note {
            margin-top: 12px;
            font-size: 15px;
            color: #ffdba4;
            line-height: 1.55;
        }

        .site-footer-bottom {
            border-top: 1px solid #b95a20;
            text-align: center;
            padding: 16px 0 18px;
            color: #ffd29d;
            font-size: 15px;
        }

        .faq-assistant {
            position: fixed;
            right: 22px;
            bottom: 22px;
            z-index: 45;
            font-family: "Nunito", "Segoe UI", sans-serif;
        }

        .faq-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            border: 1px solid #b95a20;
            border-radius: 999px;
            background: var(--primary);
            color: #fff;
            padding: 12px 16px;
            font-weight: 900;
            box-shadow: 0 14px 32px rgba(99, 45, 13, 0.24);
            cursor: pointer;
            line-height: 1;
        }

        .faq-toggle:hover {
            background: var(--primary-dark);
        }

        .faq-toggle-icon {
            width: 22px;
            height: 22px;
            display: block;
            flex: 0 0 22px;
            overflow: visible;
            stroke: currentColor;
            stroke-width: 1.9;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .faq-panel {
            position: absolute;
            right: 0;
            bottom: 58px;
            width: min(360px, calc(100vw - 28px));
            max-height: min(620px, calc(100vh - 120px));
            display: none;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 18px;
            background: #fffaf2;
            box-shadow: 0 24px 60px rgba(79, 38, 12, 0.24);
        }

        .faq-assistant.is-open .faq-panel {
            display: block;
        }

        .faq-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            padding: 16px;
            color: #fff;
            background: linear-gradient(135deg, #a84f1f 0%, #d87938 100%);
        }

        .faq-header strong {
            display: block;
            font-size: 16px;
            line-height: 1.25;
        }

        .faq-header span {
            display: block;
            margin-top: 4px;
            color: #fff3dc;
            font-size: 13px;
            line-height: 1.35;
        }

        .faq-close {
            width: 30px;
            height: 30px;
            border: 0;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.16);
            color: #fff;
            font-size: 22px;
            line-height: 1;
            cursor: pointer;
        }

        .faq-body {
            max-height: calc(min(620px, calc(100vh - 120px)) - 84px);
            overflow-y: auto;
            padding: 14px;
        }

        .faq-message {
            margin-bottom: 12px;
            padding: 12px;
            border-radius: 14px;
            background: #fff1db;
            border: 1px solid #efd0a4;
            color: #4f3421;
            font-size: 14px;
            line-height: 1.55;
        }

        .faq-question-list {
            display: grid;
            gap: 8px;
        }

        .faq-question {
            width: 100%;
            border: 1px solid #ead1ae;
            border-radius: 12px;
            background: #fff;
            color: var(--text);
            padding: 10px 12px;
            text-align: left;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            transition: background 0.15s ease, border-color 0.15s ease;
        }

        .faq-question:hover,
        .faq-question.is-active {
            background: #fff0d8;
            border-color: #d48a4d;
        }

        .faq-answer {
            margin-top: 12px;
            padding: 13px;
            border-radius: 14px;
            background: #f7ead8;
            border: 1px solid #e3c49b;
            color: #4f3421;
            font-size: 14px;
            line-height: 1.6;
            transition: box-shadow 0.18s ease, border-color 0.18s ease;
        }

        .faq-answer.is-highlighted {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(201, 106, 49, 0.16);
        }

        .faq-answer strong {
            display: block;
            margin-bottom: 5px;
            color: var(--heading);
        }

        .faq-answer a {
            color: var(--primary-dark);
            font-weight: 900;
            text-decoration: none;
        }

        .faq-answer a:hover {
            text-decoration: underline;
        }

        .faq-answer-hint {
            display: none;
            margin: 10px 0 0;
            padding: 8px 10px;
            border-radius: 10px;
            background: #fff4e3;
            color: #7a421d;
            font-size: 13px;
            font-weight: 800;
        }

        .faq-answer-hint.is-visible {
            display: block;
        }

        @media (max-width: 860px) {
            .navbar-inner {
                gap: 8px;
                padding: 10px 0;
            }

            .navbar-top {
                display: grid;
                grid-template-columns: 1fr auto auto;
                align-items: center;
                gap: 10px;
            }

            .navbar-bottom {
                display: grid;
                position: fixed;
                top: 0;
                right: 0;
                z-index: 60;
                width: min(82vw, 320px);
                height: 100dvh;
                grid-template-columns: 1fr;
                align-content: start;
                gap: 12px;
                padding: 82px 18px 24px;
                border-left: 1px solid var(--border);
                border-radius: 0;
                background: #fffaf2;
                box-shadow: -18px 0 34px rgba(90, 44, 20, 0.16);
                overflow-y: auto;
                transform: translateX(105%);
                transition: transform 0.24s ease;
            }

            .navbar.is-mobile-open .navbar-bottom {
                transform: translateX(0);
            }

            .mobile-nav-overlay {
                display: block;
                position: fixed;
                inset: 0;
                z-index: 50;
                background: rgba(59, 42, 31, 0.24);
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.2s ease;
            }

            .navbar.is-mobile-open .mobile-nav-overlay {
                opacity: 1;
                pointer-events: auto;
            }

            .navbar-left-spacer {
                display: none;
            }

            .nav-search {
                width: 100%;
            }

            .navbar-search-row {
                grid-column: 1 / -1;
                width: 100%;
            }

            .brand-wrap {
                justify-content: flex-start;
                min-width: 0;
            }

            .brand {
                font-size: 30px;
            }

            .mobile-menu-toggle {
                display: inline-flex;
                position: relative;
                z-index: 70;
            }

            .mobile-cart-shortcut,
            .nav-chat-shortcut {
                width: 42px;
                height: 42px;
                flex: 0 0 42px;
                overflow: visible;
                border: 1px solid var(--border);
                border-radius: 50%;
                background: #fff;
                color: var(--primary-dark);
                box-shadow: 0 4px 12px rgba(90, 44, 20, 0.08);
            }

            .icon-nav-link.mobile-notification-shortcut {
                display: inline-flex;
                position: relative;
                z-index: 70;
                width: 42px;
                height: 42px;
                border: 1px solid var(--border);
                border-radius: 999px;
                background: #fff;
                color: var(--primary-dark);
                box-shadow: 0 4px 12px rgba(90, 44, 20, 0.08);
            }

            .navbar-tools .desktop-notification-link {
                display: none;
            }

            .menu,
            .navbar-tools {
                width: 100%;
                justify-content: stretch;
            }

            .menu {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 8px;
            }

            .menu a,
            .navbar-tools > a:not(.icon-nav-link),
            .navbar-tools .btn-link {
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 42px;
                border: 1px solid #f0dac0;
                background: #fff;
                text-align: center;
            }

            .navbar-tools {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 8px;
            }

            .navbar-tools .icon-nav-link {
                width: 100%;
                height: 42px;
                border: 1px solid #f0dac0;
                background: #fff;
            }

            .navbar-tools form {
                display: block !important;
            }

            .navbar-tools form .btn-link {
                width: 100%;
            }

            .home-hero,
            .home-story-grid {
                grid-template-columns: 1fr;
            }

            .home-hero {
                min-height: auto;
                padding: 18px;
            }

            .home-hero-media {
                height: 240px;
                min-height: 240px;
            }

            .home-hero-content .muted {
                font-size: 16px;
            }

            .home-why-grid,
            .site-footer-inner {
                grid-template-columns: 1fr;
            }

            .about-hero,
            .about-highlights,
            .about-split {
                grid-template-columns: 1fr;
            }

            .about-benefits-copy {
                font-size: 16px;
                line-height: 1.8;
            }

            .about-benefits-section {
                padding: 30px 0;
            }

            .about-banner,
            .about-banner-photo {
                min-height: 200px;
            }

            .about-hero {
                padding: 18px;
            }

            .about-hero-media {
                width: 100%;
                justify-self: stretch;
                height: 240px;
                min-height: 240px;
            }

            .home-hero-media,
            .home-story-media {
                min-height: 190px;
            }

            .home-story-media {
                height: 240px;
                min-height: 240px;
            }

            .site-footer {
                text-align: center;
            }

            .site-footer-brand {
                justify-content: center;
            }

            .site-footer-contact {
                justify-content: center;
            }

            .site-footer-note {
                max-width: 100%;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .faq-assistant {
                right: 14px;
                bottom: 14px;
            }
        }

        @media (max-width: 520px) {
            .container {
                width: min(100% - 24px, 1200px);
            }

            .brand {
                font-size: 28px;
            }

            .brand-mark-img {
                width: 38px;
                height: 38px;
            }

            .mobile-menu-toggle {
                width: 40px;
                height: 40px;
            }

            .mobile-cart-shortcut,
            .nav-chat-shortcut,
            .icon-nav-link.mobile-notification-shortcut {
                width: 40px;
                height: 40px;
                flex-basis: 40px;
            }

            .menu,
            .navbar-tools {
                grid-template-columns: 1fr;
            }

            .faq-toggle {
                width: 48px;
                height: 48px;
                justify-content: center;
                padding: 0;
            }

            .faq-toggle-text {
                display: none;
            }

            .faq-panel {
                bottom: 58px;
            }
        }
    </style>
</head>
<body>
    @php
        $cartItemCount = auth()->check() && ! auth()->user()->is_admin
            ? \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity')
            : collect(session('cart', []))->sum('quantity');
        $unreadNotificationCount = auth()->check() && ! auth()->user()->is_admin
            ? \App\Models\UserNotification::where('user_id', auth()->id())->whereNull('read_at')->count()
            : 0;
        $unreadChatCount = auth()->check() && ! auth()->user()->is_admin
            ? \App\Models\ChatMessage::whereHas('conversation', fn ($query) => $query->where('user_id', auth()->id()))
                ->whereHas('sender', fn ($query) => $query->where('is_admin', true))
                ->whereNull('read_at')
                ->count()
            : 0;
    @endphp

    <header class="navbar">
        <div class="container navbar-inner">
            <div class="navbar-top">
                <a class="brand-wrap" href="{{ route('home') }}">
                    <span class="brand-mark" aria-hidden="true">
                        <img class="brand-mark-img" src="{{ asset('image/logosc2.png') }}" alt="">
                    </span>
                    <span class="brand">Sejucara</span>
                </a>
                @auth
                    @unless(auth()->user()->is_admin)
                        <a class="icon-nav-link mobile-notification-shortcut" href="{{ route('notifications.index') }}" aria-label="Notifikasi">
                            <svg class="cart-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M18 8a6 6 0 1 0-12 0c0 7-3 8-3 8h18s-3-1-3-8"></path>
                                <path d="M13.7 21a2 2 0 0 1-3.4 0"></path>
                            </svg>
                            @if($unreadNotificationCount > 0)
                                <span class="cart-badge">{{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}</span>
                            @endif
                        </a>
                    @endunless
                @endauth
                <button class="mobile-menu-toggle" type="button" aria-expanded="false" aria-controls="mobile-nav-panel" aria-label="Buka menu navigasi" data-mobile-nav-toggle>
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M4 7h16"></path>
                        <path d="M4 12h16"></path>
                        <path d="M4 17h16"></path>
                    </svg>
                </button>
                <div class="navbar-search-row">
                    <form class="nav-search" action="{{ route('products.index') }}" method="GET">
                        <input type="text" name="q" placeholder="Cari produk..." value="{{ request('q') }}">
                        <button type="submit">Cari</button>
                    </form>

                    @auth
                        @unless(auth()->user()->is_admin)
                            <a class="icon-nav-link nav-chat-shortcut" href="{{ route('chat.index') }}" aria-label="Live Chat" title="Live Chat">
                                <svg class="chat-nav-icon" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M20 15a3 3 0 0 1-3 3H9l-5 3v-3.8A3 3 0 0 1 2 14V7a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3z"></path>
                                    <path d="M7 9h8"></path>
                                    <path d="M7 13h5"></path>
                                </svg>
                                @if($unreadChatCount > 0)
                                    <span class="cart-badge">{{ $unreadChatCount > 99 ? '99+' : $unreadChatCount }}</span>
                                @endif
                            </a>
                        @endunless
                    @endauth

                    @if(! auth()->check() || ! auth()->user()->is_admin)
                        <a class="icon-nav-link mobile-cart-shortcut" href="{{ route('cart.index') }}" aria-label="Keranjang">
                            <svg class="cart-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <circle cx="9" cy="20" r="1"></circle>
                                <circle cx="18" cy="20" r="1"></circle>
                                <path d="M3 4h2l2.4 10.2a1 1 0 0 0 1 .8h9.7a1 1 0 0 0 1-.8L21 7H7"></path>
                            </svg>
                            @if($cartItemCount > 0)
                                <span class="cart-badge">{{ $cartItemCount > 99 ? '99+' : $cartItemCount }}</span>
                            @endif
                        </a>
                    @endif
                </div>
            </div>

            <div class="navbar-bottom" id="mobile-nav-panel">
                <div class="navbar-left-spacer" aria-hidden="true"></div>
                <nav class="menu">
                    <a class="{{ request()->routeIs('home') ? 'is-active' : '' }}" href="{{ route('home') }}">Beranda</a>
                    <a class="{{ request()->routeIs('products.*') ? 'is-active' : '' }}" href="{{ route('products.index') }}">Produk</a>
                    <a class="{{ request()->routeIs('articles.*') ? 'is-active' : '' }}" href="{{ route('articles.index') }}">Artikel</a>
                    <a class="{{ request()->routeIs('forum.*') ? 'is-active' : '' }}" href="{{ route('forum.index') }}">Forum</a>
                    <a class="{{ request()->routeIs('contact') ? 'is-active' : '' }}" href="{{ route('contact') }}">Kontak</a>
                </nav>

                <div class="navbar-tools">
                    @if(auth()->check())
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
                            <form action="{{ route('admin.logout') }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="btn-link" type="submit">Logout Admin</button>
                            </form>
                        @else
                            <a class="icon-nav-link desktop-notification-link" href="{{ route('notifications.index') }}" aria-label="Notifikasi">
                                <svg class="cart-icon" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M18 8a6 6 0 1 0-12 0c0 7-3 8-3 8h18s-3-1-3-8"></path>
                                    <path d="M13.7 21a2 2 0 0 1-3.4 0"></path>
                                </svg>
                                @if($unreadNotificationCount > 0)
                                    <span class="cart-badge">{{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}</span>
                                @endif
                            </a>
                            <a href="{{ route('orders.index') }}">Pesanan</a>
                            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="btn-link" type="submit">Logout</button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('register') }}">Registrasi</a>
                        @if(app()->environment('local'))
                            <a href="{{ url('/admin/login') }}">Admin</a>
                        @endif
                        <a href="{{ route('login') }}">Login</a>
                    @endif
                </div>
            </div>
            <button class="mobile-nav-overlay" type="button" aria-label="Tutup menu navigasi" data-mobile-nav-overlay></button>
        </div>
    </header>

    <main>
        <div class="container">
            @if(session('success'))
                <div class="flash flash-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="flash flash-error">
                    <ul style="margin:0; padding-left: 18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="site-footer">
        @php
            $footerWhatsappUrl = 'https://wa.me/6283181197907?text='.rawurlencode('Halo Sejucara, saya mau pesan produk Sejucara. Bisa dibantu informasinya?');
            $footerInstagramUrl = 'https://www.instagram.com/sejucara/';
            $footerTiktokUrl = 'https://www.tiktok.com/@sejucara.id?is_from_webapp=1&sender_device=pc';
        @endphp
        <div class="container site-footer-inner">
            <div>
                <div class="site-footer-brand">
                    <span class="brand-mark" aria-hidden="true">
                        <img class="brand-mark-img" src="{{ asset('image/logosc2.png') }}" alt="">
                    </span>
                    <span class="brand">Sejucara</span>
                </div>
                <p class="site-footer-note">Hadirkan kesegaran alami dalam setiap tegukan. Sejucara, minuman tradisional dengan sentuhan modern khas Nusantara.</p>
            </div>
            <div>
                <h4>Navigasi</h4>
                <div class="site-footer-links">
                    <a href="{{ route('home') }}">Beranda</a>
                    <a href="{{ route('about') }}">Tentang</a>
                    <a href="{{ route('products.index') }}">Produk</a>
                    <a href="{{ route('contact') }}">Kontak</a>
                </div>
            </div>
            <div>
                <h4>Hubungi Kami</h4>
                <div class="site-footer-contact">
                    <a href="mailto:sejucara26@gmail.com" aria-label="Email Sejucara" title="Email">
                        <svg class="footer-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2zm0 2v.5l8 4.8 8-4.8V8l-8 4.8L4 8.1z"/></svg>
                    </a>
                    <a href="{{ $footerWhatsappUrl }}" target="_blank" rel="noopener" aria-label="WhatsApp Sejucara" title="WhatsApp">
                        <svg class="footer-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.8 14.7L2 22l5.5-1.1A10 10 0 1 0 12 2zm5.6 14.1c-.2.6-1.2 1-1.7 1.1-.4.1-1 .1-1.6-.1-.4-.1-.9-.3-1.6-.6-2.8-1.2-4.6-4.1-4.8-4.3-.2-.3-1.1-1.5-1.1-2.9s.7-2 1-2.3c.3-.3.6-.4.8-.4h.6c.2 0 .4 0 .6.5.2.6.7 2 .7 2.2.1.2.1.4 0 .6-.1.2-.2.4-.4.6-.2.2-.3.3-.4.5-.2.2-.4.4-.2.7.2.3.8 1.4 1.8 2.2 1.2 1 2.1 1.3 2.4 1.4.3.1.5.1.7-.1.2-.2.8-.9 1-1.2.2-.3.4-.2.7-.1.3.1 1.8.9 2.1 1 .3.2.5.3.6.5.1.1.1.9-.1 1.5z"/></svg>
                    </a>
                    <a href="{{ $footerInstagramUrl }}" target="_blank" rel="noopener" aria-label="Instagram Sejucara" title="Instagram">
                        <svg class="footer-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm10 2H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3zm-5 3.5A5.5 5.5 0 1 1 6.5 13 5.5 5.5 0 0 1 12 7.5zm0 2A3.5 3.5 0 1 0 15.5 13 3.5 3.5 0 0 0 12 9.5zM18 6.6a1.2 1.2 0 1 1-1.2 1.2A1.2 1.2 0 0 1 18 6.6z"/></svg>
                    </a>
                    <a href="https://facebook.com/sejucara" target="_blank" rel="noopener" aria-label="Facebook Sejucara" title="Facebook">
                        <svg class="footer-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M14 8h2V5h-2.4C10.9 5 10 6.7 10 8.6V11H8v3h2v7h3v-7h2.4l.6-3h-3V8.8c0-.6.2-.8 1-.8z"/></svg>
                    </a>
                    <a href="{{ $footerTiktokUrl }}" target="_blank" rel="noopener" aria-label="TikTok Sejucara" title="TikTok">
                        <svg class="footer-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M15 3c.4 2.7 1.8 4.3 4 4.7V11c-1.6-.1-3-.6-4-1.5v5.7c0 3.1-2.1 5.3-5.1 5.3A5 5 0 0 1 5 15.4c0-3.1 2.3-5.2 5.5-5.1v3.3c-1.3-.1-2.2.7-2.2 1.8 0 1 .7 1.8 1.7 1.8 1.1 0 1.8-.7 1.8-2.1V3h3.2z"/></svg>
                    </a>
                </div>
                <p class="site-footer-contact-note">Butuh bantuan? Hubungi kami lewat kanal pilihanmu.</p>
            </div>
        </div>
        <div class="site-footer-bottom">
            &copy; {{ date('Y') }} <strong>sejucara</strong>. Semua Hak Dilindungi.
        </div>
    </footer>

    <script>
        (() => {
            const navbar = document.querySelector('.navbar');
            const toggle = document.querySelector('[data-mobile-nav-toggle]');
            const panel = document.getElementById('mobile-nav-panel');
            const overlay = document.querySelector('[data-mobile-nav-overlay]');

            if (!navbar || !toggle || !panel) {
                return;
            }

            const closeMenu = () => {
                navbar.classList.remove('is-mobile-open');
                toggle.setAttribute('aria-expanded', 'false');
            };

            toggle.addEventListener('click', () => {
                const isOpen = navbar.classList.toggle('is-mobile-open');
                toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });

            panel.querySelectorAll('a, button[type="submit"]').forEach((item) => {
                item.addEventListener('click', closeMenu);
            });

            overlay?.addEventListener('click', closeMenu);

            document.addEventListener('click', (event) => {
                if (!navbar.contains(event.target)) {
                    closeMenu();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeMenu();
                }
            });
        })();
    </script>

    @unless(auth()->check() && auth()->user()->is_admin)
        <div class="faq-assistant" id="faq-assistant">
            <div class="faq-panel" id="faq-panel" aria-hidden="true">
                <div class="faq-header">
                    <div>
                        <strong>Asisten Bantuan Sejucara</strong>
                        <span>Pilih pertanyaan yang sering ditanyakan pembeli.</span>
                    </div>
                    <button class="faq-close" type="button" aria-label="Tutup bantuan" data-faq-close>&times;</button>
                </div>
                <div class="faq-body">
                    <div class="faq-message">
                        Halo, saya bisa bantu jawab pertanyaan umum seputar produk, pemesanan, pembayaran, pengiriman, dan forum diskusi.
                    </div>

                    <div class="faq-question-list">
                        <button class="faq-question" type="button" data-faq-question="order">Bagaimana cara memesan produk?</button>
                        <button class="faq-question" type="button" data-faq-question="login">Apakah harus login untuk belanja?</button>
                        <button class="faq-question" type="button" data-faq-question="payment">Pembayaran menggunakan apa?</button>
                        <button class="faq-question" type="button" data-faq-question="proof">Bagaimana cara upload bukti pembayaran?</button>
                        <button class="faq-question" type="button" data-faq-question="shipping">Berapa estimasi pesanan tiba?</button>
                        <button class="faq-question" type="button" data-faq-question="receipt">Kapan nomor resi didapatkan?</button>
                        <button class="faq-question" type="button" data-faq-question="productType">Apa bedanya Cascara Kering dan Siap Minum?</button>
                        <button class="faq-question" type="button" data-faq-question="brew">Bagaimana cara seduh teh cascara?</button>
                        <button class="faq-question" type="button" data-faq-question="stock">Bagaimana kalau produk habis?</button>
                        <button class="faq-question" type="button" data-faq-question="status">Bagaimana melihat status pesanan?</button>
                        <button class="faq-question" type="button" data-faq-question="afterCheckout">Apa yang dilakukan setelah checkout?</button>
                        <button class="faq-question" type="button" data-faq-question="forum">Untuk apa forum diskusi?</button>
                        <button class="faq-question" type="button" data-faq-question="contact">Kalau jawaban belum cukup harus ke mana?</button>
                    </div>

                    <div class="faq-answer-hint" id="faq-answer-hint">Jawaban ditampilkan di bawah. Silakan scroll sedikit jika belum terlihat.</div>

                    <div class="faq-answer" id="faq-answer" aria-live="polite">
                        <strong>Pilih salah satu pertanyaan.</strong>
                        Jawaban akan muncul di sini secara otomatis.
                    </div>
                </div>
            </div>

            <button class="faq-toggle" type="button" aria-expanded="false" aria-controls="faq-panel" data-faq-toggle>
                <svg class="faq-toggle-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"></circle>
                    <path d="M9.6 9.2a2.6 2.6 0 0 1 5.1.8c0 1.8-1.4 2.4-2.2 3.1-.5.4-.6.8-.6 1.4"></path>
                    <path d="M12 17.4h.01"></path>
                </svg>
                <span class="faq-toggle-text">Bantuan</span>
            </button>
        </div>

        <script>
            (() => {
                const assistant = document.getElementById('faq-assistant');
                const panel = document.getElementById('faq-panel');
                const toggle = document.querySelector('[data-faq-toggle]');
                const closeButton = document.querySelector('[data-faq-close]');
                const answerBox = document.getElementById('faq-answer');
                const answerHint = document.getElementById('faq-answer-hint');
                const questions = document.querySelectorAll('[data-faq-question]');

                if (!assistant || !panel || !toggle || !answerBox) {
                    return;
                }

                const answers = {
                    order: {
                        title: 'Cara memesan produk',
                        text: 'Buka halaman Produk, pilih produk yang diinginkan, klik ikon keranjang atau buka detail produk, lalu lanjutkan ke halaman Keranjang dan Checkout.'
                    },
                    login: {
                        title: 'Login sebelum belanja',
                        text: 'Produk bisa dilihat tanpa login, tetapi untuk checkout dan menyimpan riwayat pesanan, pembeli perlu login atau membuat akun terlebih dahulu.'
                    },
                    payment: {
                        title: 'Metode pembayaran',
                        text: 'Pembayaran di Sejucara menggunakan QRIS. QRIS adalah metode pembayaran dengan cara scan kode QR melalui aplikasi mobile banking atau dompet digital yang mendukung QRIS. Pada halaman Checkout, gambar QRIS bisa diklik untuk diperbesar dan bisa diunduh agar lebih mudah discan.'
                    },
                    proof: {
                        title: 'Upload bukti pembayaran',
                        text: 'Setelah membayar melalui QRIS, upload foto bukti pembayaran pada form Checkout. Pesanan akan menunggu verifikasi admin sebelum diproses.'
                    },
                    shipping: {
                        title: 'Estimasi pesanan tiba',
                        text: 'Estimasi pengiriman: Jawa Barat 1-2 hari kerja, Pulau Jawa 2-4 hari kerja, dan luar Pulau Jawa 4-7 hari kerja setelah pembayaran diverifikasi.'
                    },
                    receipt: {
                        title: 'Nomor resi',
                        text: 'Nomor resi akan tersedia setelah pembayaran diverifikasi, paket disiapkan, dan admin menerima resi resmi dari pihak ekspedisi.'
                    },
                    productType: {
                        title: 'Jenis produk cascara',
                        text: 'Cascara Kering biasanya perlu diseduh terlebih dahulu. Cascara Siap Minum adalah produk minuman yang lebih praktis untuk langsung dinikmati.'
                    },
                    brew: {
                        title: 'Cara seduh teh cascara',
                        text: 'Gunakan sekitar 1-2 sendok teh cascara kering, seduh dengan air panas kurang lebih 200 ml selama 5-8 menit, lalu saring dan nikmati. Bisa juga ditambahkan madu atau es sesuai selera.'
                    },
                    stock: {
                        title: 'Produk habis',
                        text: 'Jika stok produk habis, produk tidak dapat dipesan sementara. Silakan cek kembali katalog produk atau hubungi Sejucara untuk informasi restock.'
                    },
                    status: {
                        title: 'Melihat status pesanan',
                        text: 'Setelah login, buka menu Pesanan untuk melihat status pembayaran, proses pesanan, pengiriman, dan nomor resi jika sudah tersedia.'
                    },
                    afterCheckout: {
                        title: 'Setelah checkout',
                        text: 'Setelah checkout berhasil, tunggu admin memverifikasi bukti pembayaran. Jika pembayaran sudah sesuai, pesanan akan diproses dan status pesanan akan diperbarui.'
                    },
                    forum: {
                        title: 'Fungsi forum diskusi',
                        text: 'Forum dipakai untuk bertanya, berdiskusi, memberi saran, dan mendapatkan jawaban dari admin atau pengguna lain terkait produk dan layanan Sejucara.'
                    },
                    contact: {
                        title: 'Butuh bantuan lanjutan',
                        text: 'Jika pertanyaan belum terjawab, pengguna yang sudah login dapat memakai menu Live Chat untuk berbicara dengan admin. Kamu juga dapat menggunakan halaman Kontak, WhatsApp, atau Forum Diskusi Sejucara.'
                    }
                };

                const setOpen = (isOpen) => {
                    assistant.classList.toggle('is-open', isOpen);
                    panel.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
                    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                };

                toggle.addEventListener('click', () => {
                    setOpen(!assistant.classList.contains('is-open'));
                });

                closeButton.addEventListener('click', () => {
                    setOpen(false);
                });

                questions.forEach((button) => {
                    button.addEventListener('click', () => {
                        const answer = answers[button.dataset.faqQuestion];

                        if (!answer) {
                            return;
                        }

                        questions.forEach((item) => item.classList.remove('is-active'));
                        button.classList.add('is-active');
                        answerBox.innerHTML = `<strong>${answer.title}</strong>${answer.text}`;
                        answerHint?.classList.add('is-visible');
                        answerBox.classList.add('is-highlighted');
                        answerBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

                        window.setTimeout(() => {
                            answerBox.classList.remove('is-highlighted');
                        }, 900);
                    });
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape') {
                        setOpen(false);
                    }
                });
            })();
        </script>
    @endunless
</body>
</html>
