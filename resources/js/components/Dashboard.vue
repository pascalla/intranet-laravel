<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-9">
        <timetable-dashboard></timetable-dashboard>
      </div>
      <div class="col-lg-3">
        <div class="card mt-5">
          <h5 class="card-header">Upcoming Coursework</h5>
          <div class="card-body">
            <router-link to="/coursework"><a href="#" class="btn btn-primary">View all</a></router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import TimetableDashboard from "./TimetableDashboard";

export default {
  name: 'dashboard',
  data () {
    return {

    }
  },
  components: {
    'timetable-dashboard': TimetableDashboard
  }
}
</script>
