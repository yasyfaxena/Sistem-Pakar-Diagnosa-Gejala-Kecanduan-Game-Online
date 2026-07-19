<div class="navbar">
   <div class="logo">
   <a href="#" class="brand">
                <img src="sipagon.png" style="width: 70px; height: 70px;" alt="Logo SIPAGON">
                <div class="brand-text">
                    <div class="title">SIPAGON</div>
                    <div class="subtitle">Sistem Pakar Game Online</div>
                </div>
            </a>
   </div>
   <div class="nav-links">
       <a href="dashboard_admin.php"><i class="fa fa-home"></i> Home</a>
       <a href="hipotesis.php"><i class="fa fa-sitemap"></i> Hipotesis</a>
       <a href="gejala.php"><i class="fa fa-list"></i> Gejala</a>
       <a href="basis_pengetahuan.php"><i class="fa fa-database"></i> Basis Pengetahuan</a>
       <a href="daftar_pasien.php"><i class="fa fa-users"></i> Pasien</a>
       <a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a>
   </div>
   </div>

<style>
   .navbar {
       background: linear-gradient(to right, #0B2F9F, rgb(57, 99, 227));
       padding: 1rem 2rem;
       display: flex;
       justify-content: space-between;
       align-items: center;
       box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
       position: fixed;
       top: 0;
       left: 0;
       right: 0;
       margin: 0;
       width: 100%;
       box-sizing: border-box;
   }

   .nav-container {
       display: flex;
       align-items: center;
       gap: 1rem;
       padding-left: 1rem;
   }

   .brand {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: white;
            text-decoration: none;
            margin-left: -15px;
        }

        .brand img {
            height: 50px;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
        }

        .brand-text .title {
            font-size: 24px;
            font-weight: bold;
            line-height: 1.2;
        }

        .brand-text .subtitle {
            font-size: 14px;
            opacity: 0.9;
            margin-top: -2px;
        }

   .nav-links {
       margin-left: auto;
   }

   .nav-links a {
       color: white;
       text-decoration: none;
       padding: 0.5rem 1rem;
       margin: 0 0.5rem;
       border-radius: 5px;
       transition: all 0.3s ease;
   }

   .nav-links a:hover {
       background: rgba(255, 255, 255, 0.1);
   }

   .nav-links a.active {
       background: rgba(255, 255, 255, 0.2);
       font-weight: bold;
   }
   
   body {
       margin: 0;
       padding-top: 90px;
   }

   @media (max-width: 768px) {
       .navbar {
           padding: 1rem;
       }
       
       .nav-links {
           gap: 0.5rem;
       }
       
       .nav-links a {
           padding: 0.4rem 0.8rem;
           margin: 0 0.2rem;
           font-size: 0.9rem;
       }
   }
</style>