<!-- Modal Logout -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-2xl shadow-xl overflow-hidden">
            <div class="modal-header border-b border-gray-100 bg-gray-50 px-6 py-4">
                <h5 class="modal-title font-bold text-indigo-950 text-base" id="exampleModalLabel">Konfirmasi Logout</h5>
                <button type="button" class="close text-gray-400 focus:outline-none" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-6 text-gray-600 text-sm">
                Apakah Anda yakin ingin keluar dari akun Anda?
            </div>
            <div class="modal-footer border-t border-gray-100 bg-gray-50 px-6 py-3 flex gap-x-2">
                <button type="button" class="px-5 py-2 rounded-full border border-gray-300 text-gray-700 text-xs font-semibold hover:bg-gray-100" data-dismiss="modal">Batal</button>
                <a href="{{ url('logout') }}" class="px-5 py-2 rounded-full bg-red-600 text-white text-xs font-semibold hover:bg-red-700 transition-colors">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- General JS Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

<!-- DataTables JS -->
<script src="{{ asset('assets/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/datatables.net-select-bs4/js/select.bootstrap4.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // Slide-in Mobile Drawer open/close handling
        function openMobileDrawer() {
            $('#mobile-drawer-overlay').removeClass('hidden').addClass('opacity-100').removeClass('opacity-0');
            $('#mobile-drawer').removeClass('-translate-x-full');
            $('body').addClass('overflow-hidden');
        }

        function closeMobileDrawer() {
            $('#mobile-drawer').addClass('-translate-x-full');
            $('#mobile-drawer-overlay').addClass('opacity-0').removeClass('opacity-100');
            setTimeout(function() {
                $('#mobile-drawer-overlay').addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }, 300);
        }

        $('#mobile-menu-toggle').on('click', function(e) {
            e.preventDefault();
            openMobileDrawer();
        });

        $('#mobile-drawer-close, #mobile-drawer-overlay').on('click', function() {
            closeMobileDrawer();
        });

        $('.modal').appendTo('body');
    });
</script>

@stack('scripts')

<!-- PWA Service Worker & Install Prompt -->
@include('partials.pwa_install_prompt')
