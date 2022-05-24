<div class="sidebar">
    <div class="logo-details">
      
        <div class="logo_name">Manager Control</div>
        <i class='bx bx-menu' id="btn" ></i>
    </div>
    <ul class="nav-list">
      <li>
          <i class='bx bx-search' ></i>
         <input type="text" placeholder="Search...">
         <span class="tooltip">Search</span>
      </li>
      <li>
        <a href="{{ route('dashboard') }}">
          <i class='bx bx-grid-alt'></i>
          <span class="links_name">Dashboard</span>
        </a>
         <span class="tooltip">Dashboard</span>
      </li>
      <li>
        <a href="{{ route('analise') }}">
          <i class='bx bx-pie-chart-alt-2' ></i>
          <span class="links_name">Análise</span>
        </a>
        <span class="tooltip">Análise</span>
      </li>
      <li>
        <a href="{{ route('quadro') }}">
          <i class='bx bx-card' ></i>
          <span class="links_name">Quadro</span>
        </a>
        <span class="tooltip">Quadro</span>
      </li>
      <li>
        <a href="{{ route('etiqueta') }}">
          <i class='bx bx-purchase-tag-alt'></i>
          <span class="links_name">Etiqueta</span>
        </a>
        <span class="tooltip">Etiqueta</span>
      </li>
      <li>
        @auth
          @if( Auth::user()->id_user_tipo==1 )
            <a href="{{ route('user') }}">
          @else
            <a href="{{ route('form_editar_user', 
              ['id' => Auth::user()->id_user]) 
            }}">
          @endif
        @endauth
         <i class='bx bx-user' ></i>
         <span class="links_name">Usuário</span>
       </a>
       <span class="tooltip">Usuário</span>
     </li>
     <li>
        <a href="{{ route('sair') }}">
          <i class='bx bx-log-out' id="log_out"></i>
          <span class="links_name">Sair</span>
        </a>
        <span class="tooltip">Sair</span>
     </li>
    </ul>
  </div>

  <script>
    let sidebar = document.querySelector(".sidebar");
    let closeBtn = document.querySelector("#btn");
    let searchBtn = document.querySelector(".bx-search");
  
    closeBtn.addEventListener("click", ()=>{
      sidebar.classList.toggle("open");
      menuBtnChange();//calling the function(optional)
    });
  
    searchBtn.addEventListener("click", ()=>{ // Sidebar open when you click on the search iocn
      sidebar.classList.toggle("open");
      menuBtnChange(); //calling the function(optional)
    });
  
    // following are the code to change sidebar button(optional)
    function menuBtnChange() {
     if(sidebar.classList.contains("open")){
       closeBtn.classList.replace("bx-menu", "bx-menu-alt-right");//replacing the iocns class
     }else {
       closeBtn.classList.replace("bx-menu-alt-right","bx-menu");//replacing the iocns class
     }
    }
  </script>