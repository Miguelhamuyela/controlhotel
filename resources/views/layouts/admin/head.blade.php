<!DOCTYPE html>
<html lang="pt-pt" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'INFOSI RH - Dashboard')</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/theme.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        [data-theme="dark"] {
            --bg-color: #1a1d21;
            --text-color: #ffffff;
            --card-bg: #212529;
            --sidebar-bg: #1a1d21;
            --navbar-bg: #1a1d21;
            --footer-bg: #1a1d21;
            background-color: var(--bg-color);
            color: var(--text-color);
        }
        [data-theme="light"] {
            --bg-color: #ffffff;
            --text-color: #000000;
            --card-bg: #f8f9fa;
            --sidebar-bg: #ffffff;
            --navbar-bg: #ffffff;
            --footer-bg: #1a1d21;
            background-color: var(--bg-color);
            color: var(--text-color);
        }
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 250px;
            background-color: var(--sidebar-bg);
            overflow-y: auto;
            transition: width 0.3s ease;
            z-index: 1000;
        }
        .sidebar .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--text-color);
            transition: background 0.2s ease;
        }
        .sidebar .nav-link:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }
        .sidebar .has-submenu .nav-link i.ms-auto {
            transition: transform 0.3s ease;
        }
        .sidebar .has-submenu.show .nav-link i.ms-auto {
            transform: rotate(180deg);
        }
        .submenu {
            background-color: #ffffff;
            padding-left: 20px;
        }
        .submenu .nav-link {
            padding: 8px 20px;
            font-size: 0.9rem;
        }
        .submenu .nav-link:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }
        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
            min-height: calc(100vh - 60px);
            transition: margin-left 0.3s ease;
        }
        .navbar {
            background-color: var(--navbar-bg);
            border-bottom: 1px solid #e9ecef;
        }
        .navbar-dark .navbar-brand, .navbar-dark .nav-link {
            color: #007bff;
        }
        .navbar-dark .nav-link:hover {
            color: #0056b3;
        }
        .navbar-dark .btn-outline-light {
            color: #007bff;
            border-color: #007bff;
        }
        .navbar-dark .btn-outline-light:hover {
            background-color: #007bff;
            color: #ffffff;
        }
        .footer {
            background-color: var(--footer-bg);
            color: #ffffff;
            padding: 10px 0;
            width: 100%;
            position: relative;
            bottom: 0;
            clear: both;
        }
        .footer a {
            color: #007bff;
            text-decoration: none;
        }
        .footer a:hover {
            color: #0056b3;
        }
        @media (max-width: 768px) {
            .sidebar { width: 100%; transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .content-wrapper { margin-left: 0; }
        }
        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
    </style>
</head>