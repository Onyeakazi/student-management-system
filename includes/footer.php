


    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="assets/vendors/select2/select2.min.js"></script>
    <script src="assets/vendors/typeahead.js/typeahead.bundle.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="assets/vendors/chart.js/Chart.min.js"></script>
    <script src="assets/vendors/moment/moment.min.js"></script>
    <script src="assets/vendors/daterangepicker/daterangepicker.js"></script>
    <script src="assets/vendors/chartist/chartist.min.js"></script>

    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="assets/js/typeahead.js"></script>
    <script src="assets/js/select2.js"></script>
     <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- <script src="assets/js/dashboard.js"></script> -->
    <!-- End custom js for this page -->
    <script>
        function onReady(callback) {
            var intervalID = window.setInterval(checkReady, 1000);
            function checkReady() {
                if (document.getElementsByTagName('body')[0] !== undefined) {
                    window.clearInterval(intervalID);
                    callback.call(this);
                }
            }
        }

        function show(id, value) {
            document.getElementById(id).style.display = value ? 'block' : 'none';
        }

        onReady(function () {
            show('page', true);
            show('loading', false);
        });
    </script>
  </body>
</html>