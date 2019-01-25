<template>
  <div class="h-100 flex-grow d-flex flex-column">
    <header class="bmd-layout-header">
      <div class="navbar navbar-light bg-faded">
        <button class="navbar-toggler" type="button" data-toggle="drawer" data-target="#appDrawer">
          <span class="sr-only">Toggle drawer</span>
          <i class="material-icons">menu</i>
        </button>
        <ul class="nav nav-tabs">
          <li class="nav-item">
             <a class="nav-link" href="#">{{ user.name }}</a>
           </li>
           <li class="nav-item">
              <a class="nav-link" href="#">Logout</a>
            </li>
        </ul>
      </div>
    </header>

    <div class="d-flex flex-column flex-grow">
      <div class="h-100 flex-grow d-flex flex-column">
        <div class="bmd-layout-container bmd-drawer-f-l bmd-drawer-overlay">
          <div id="appDrawer" class="bmd-layout-drawer bg-faded">
            <div class="container">
              <header>
                 <a class="navbar-brand">Intranet</a>
               </header>
               <ul class="list-group">
                 <router-link to="/"><a class="list-group-item"><i class="fa fa-home" aria-hidden="true"></i>Home</a></router-link>
                 <li class="list-group-item"><i class="fa fa-calendar" aria-hidden="true"></i>Timetable</li>
                 <router-link to="/coursework"><a class="list-group-item"><i class="fa fa-book" aria-hidden="true"></i>Coursework</a></router-link>
               </ul>
             </div>
          </div>

          <main class="bmd-layout-content pb-2">
              <router-view></router-view>
          </main>
        </div>
      </div>
    </div>
  </div>
</template>



<script>
export default {
  computed: {
    user () {
      return this.$store.state.authentication.user
    }
  }
}
</script>
