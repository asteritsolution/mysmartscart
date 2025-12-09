    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script>
        // Initialize DataTables
        $(document).ready(function() {
            if ($('.data-table').length) {
                $('.data-table').DataTable({
                    "order": [[ 0, "desc" ]],
                    "pageLength": 25
                });
            }
        });
        
        // Toggle sidebar on mobile
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }
        
        // Close sidebar when clicking outside on mobile
        $(document).click(function(e) {
            if ($(window).width() <= 768) {
                if (!$(e.target).closest('.sidebar').length && !$(e.target).closest('.btn').length) {
                    $('#sidebar').removeClass('show');
                }
            }
        });
    </script>
</body>
</html>

