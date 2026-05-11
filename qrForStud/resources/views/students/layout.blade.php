<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'StudentMS')</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/students.css') }}">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        :root{
            --primary:#6366f1;
            --primary-dark:#4f46e5;
            --sidebar:#0f172a;
            --sidebar-light:#1e293b;
            --text:#e2e8f0;
            --muted:#94a3b8;
            --bg:#f8fafc;
            --card:#ffffff;
            --border:#e2e8f0;
            --shadow:0 10px 30px rgba(15,23,42,.08);
            --radius:18px;
            --transition:all .25s ease;
        }

        body{
            font-family:'Inter',sans-serif;
            background:var(--bg);
            color:#0f172a;
            display:flex;
            min-height:100vh;
        }

        /* SIDEBAR */

        .sidebar{
            width:270px;
            background:linear-gradient(180deg,#0f172a,#111827);
            position:fixed;
            top:0;
            left:0;
            bottom:0;
            display:flex;
            flex-direction:column;
            border-right:1px solid rgba(255,255,255,.06);
            z-index:100;
        }

        .sidebar-header{
            padding:28px 24px;
            border-bottom:1px solid rgba(255,255,255,.06);
        }

        .logo{
            display:flex;
            align-items:center;
            gap:12px;
        }

        .logo-icon{
            width:46px;
            height:46px;
            border-radius:14px;
            background:linear-gradient(135deg,#6366f1,#8b5cf6);
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:1.3rem;
            box-shadow:0 10px 20px rgba(99,102,241,.3);
        }

        .logo h1{
            color:white;
            font-size:1.3rem;
            font-weight:800;
        }

        .logo p{
            color:var(--muted);
            font-size:.78rem;
            margin-top:2px;
        }

        .sidebar-nav{
            padding:20px 16px;
            flex:1;
        }

        .nav-title{
            color:#64748b;
            font-size:.72rem;
            text-transform:uppercase;
            letter-spacing:1px;
            margin:10px 12px;
        }

        .nav-link{
            display:flex;
            align-items:center;
            gap:14px;
            padding:14px 16px;
            border-radius:16px;
            color:#cbd5e1;
            text-decoration:none;
            margin-bottom:10px;
            transition:var(--transition);
            font-weight:600;
        }

        .nav-link:hover{
            background:rgba(255,255,255,.06);
            color:white;
            transform:translateX(3px);
        }

        .nav-link.active{
            background:linear-gradient(135deg,#6366f1,#8b5cf6);
            color:white;
            box-shadow:0 10px 20px rgba(99,102,241,.25);
        }

        .nav-icon{
            width:20px;
            text-align:center;
            font-size:1rem;
        }

        .sidebar-footer{
            padding:20px;
            border-top:1px solid rgba(255,255,255,.06);
            color:#64748b;
            font-size:.82rem;
        }

        /* MAIN */

        .main-content{
            margin-left:270px;
            width:calc(100% - 270px);
            min-height:100vh;
            padding:32px;
        }

        .topbar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:28px;
            flex-wrap:wrap;
            gap:15px;
        }

        .topbar-title h2{
            font-size:1.8rem;
            font-weight:800;
            color:#0f172a;
        }

        .topbar-title p{
            color:#64748b;
            margin-top:4px;
            font-size:.92rem;
        }

        .topbar-user{
            display:flex;
            align-items:center;
            gap:14px;
            background:white;
            padding:10px 16px;
            border-radius:16px;
            box-shadow:var(--shadow);
        }

        .user-avatar{
            width:42px;
            height:42px;
            border-radius:50%;
            background:linear-gradient(135deg,#6366f1,#8b5cf6);
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:700;
        }

        .user-info strong{
            display:block;
            font-size:.92rem;
        }

        .user-info span{
            color:#64748b;
            font-size:.8rem;
        }

        /* ALERT */

        .alert-success{
            background:white;
            border-left:5px solid #10b981;
            padding:18px 20px;
            border-radius:16px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            margin-bottom:26px;
            box-shadow:var(--shadow);
            animation:fadeIn .4s ease;
        }

        .alert-success span{
            color:#065f46;
            font-weight:600;
        }

        .alert-close{
            border:none;
            background:none;
            font-size:1.4rem;
            cursor:pointer;
            color:#6b7280;
        }

      

        .content-wrapper{
            animation:fadeIn .4s ease;
        }

        @keyframes fadeIn{
            from{
                opacity:0;
                transform:translateY(10px);
            }
            to{
                opacity:1;
                transform:translateY(0);
            }
        }

      

        @media(max-width:900px){

            .sidebar{
                width:100%;
                height:auto;
                position:relative;
            }

            .main-content{
                margin-left:0;
                width:100%;
                padding:20px;
            }

            body{
                flex-direction:column;
            }
        }
    </style>

    @stack('head')
</head>
<body>

    
    <aside class="sidebar">

        <div class="sidebar-header">
            <div class="logo">
                <div class="logo-icon">🎓</div>
                <div>
                    <h1>StudentMS</h1>
                    <p>Management System</p>
                </div>
            </div>
        </div>

        <nav class="sidebar-nav">

            <div class="nav-title">Main Menu</div>

            <a href="{{ route('students.index') }}"
               class="nav-link {{ request()->routeIs('students.index') ? 'active' : '' }}">
                <span class="nav-icon">👥</span>
                <span>All Students</span>
            </a>

            <a href="{{ route('students.create') }}"
               class="nav-link {{ request()->routeIs('students.create') ? 'active' : '' }}">
                <span class="nav-icon">➕</span>
                <span>Add Student</span>
            </a>

        </nav>

        <div class="sidebar-footer">
            © {{ date('Y') }} StudentMS <br>
            Modern Dashboard UI
        </div>

    </aside>

   
    <main class="main-content">

        <div class="topbar">

            <div class="topbar-title">
                <h2>@yield('title', 'Dashboard')</h2>
                <p>Manage student information and QR records</p>
            </div>

            <div class="topbar-user">
                <div class="user-avatar">A</div>
                <div class="user-info">
                    <strong>Administrator</strong>
                    <span>System Access</span>
                </div>
            </div>

        </div>

       
        @if(session('success'))
            <div class="alert-success">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="alert-close">&times;</button>
            </div>
        @endif

        <div class="content-wrapper">
            @yield('content')
        </div>

    </main>

</body>
</html>