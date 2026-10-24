<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>

    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Muarra Back Office</title>
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}"> --}}
{{-- 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet"> --}}

    <link rel="stylesheet" href="{{ asset('assets/css/muarra-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/core.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.css">
    @yield('css')
</head>

<body>
  <div class="layout">
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fa-solid fa-bars"></i>
    </button>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    @include("layout.admin.sidebar")
  
    @yield("content")
  </div>

  <script>
    const openButtons = document.querySelectorAll(".open-modal");

    openButtons.forEach(btn=>{
        btn.addEventListener("click",()=>{
            const modal=document.getElementById(btn.dataset.modal);
            modal.classList.add("active");
        });
    });

    document.querySelectorAll(".modal-close, .close-modal").forEach(btn => {
        btn.addEventListener("click", () => {
            btn.closest(".modal").classList.remove("active");
        });
    });

    document.querySelectorAll(".modal").forEach(modal=>{
        modal.addEventListener("click",(e)=>{
            if(e.target===modal){
                modal.classList.remove("active");
            }
        });
    });

    document.addEventListener("keydown",(e)=>{
        if(e.key==="Escape"){
            document.querySelectorAll(".modal.active").forEach(m=>{
                m.classList.remove("active");
            });
        }
    });
  </script>

  <script>
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebarOverlay");
    const toggle = document.getElementById("sidebarToggle");

    toggle.addEventListener("click", () => {
        sidebar.classList.toggle("open");
        overlay.classList.toggle("active");
    });

    overlay.addEventListener("click", () => {
        sidebar.classList.remove("open");
        overlay.classList.remove("active");
    });

    window.addEventListener("resize", () => {
        if(window.innerWidth > 768){
            sidebar.classList.remove("open");
            overlay.classList.remove("active");
        }
    });
    </script>

  @yield("script")
</body>

</html>