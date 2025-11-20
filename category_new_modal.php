<!-- Button to trigger modal -->
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#categoryModal">
    CREER NOUVELLE CATEGORIE
</button> -->

<!-- Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Category Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded here using AJAX -->
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
    
</div>
<script>
    // Function to load content into modal
    function loadCategoryModalContent() {
        $.ajax({
            url: 'category_new.php', // Path to your category_new.php file
            type: 'GET',
            success: function(response) {
                $('#categoryModal .modal-body').html(response);
            }
        });
    }

    // Call the function when the modal is shown
    $('#categoryModal').on('show.bs.modal', function() {
        loadCategoryModalContent();
    });
</script>

