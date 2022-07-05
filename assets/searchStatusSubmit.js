const selectStatus = document.getElementById('search_admin_reservation_status');
selectStatus.onchange = function() {
    this.form.submit();
};