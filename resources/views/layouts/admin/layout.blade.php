@include('layouts.admin.head')

<body>
    @include('layouts.admin.navbar')
    @include('layouts.admin.sidebar')

    <div class="content-wrapper">
        <main class="container-fluid p-4">
            @yield('content')
        </main>
         
    @include('layouts.admin.footer') 
    </div>

    
    

    <div class="theme-toggler position-fixed bottom-0 start-0 m-3">
        <button id="themeToggle" class="btn btn-primary rounded-circle"><i class="fas fa-cog fa-spin"></i></button>
        <div class="theme-panel d-none bg-white p-3 shadow">
            <h5>Theme Settings</h5>
            <div class="mb-3">
                <label>Navigation</label>
                <select id="navTheme" class="form-select">
                    <option>Light</option>
                    <option>Dark</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Header</label>
                <select id="headerTheme" class="form-select">
                    <option>Light</option>
                    <option>Dark</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Skins</label>
                <select id="skin" class="form-select">
                    <option>Light</option>
                    <option>Dark</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Typography</label>
                <select id="typography" class="form-select">
                    <option>Inter</option>
                    <option>Roboto</option>
                </select>
            </div>
            <button class="btn btn-secondary">Reset</button>
            <button class="btn btn-primary">Apply</button>
        </div>
    </div>

    <!-- Modal Dinâmica Única -->
    <div class="modal fade" id="globalModal" tabindex="-1" aria-labelledby="globalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="globalModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/theme-customizer-init.min.js') }}"></script>
    <script>
        document.getElementById('themeToggle').addEventListener('click', function() {
            document.querySelector('.theme-panel').classList.toggle('d-none');
        });

        document.getElementById('skin').addEventListener('change', function(e) {
            document.documentElement.setAttribute('data-theme', e.target.value.toLowerCase());
        });

        document.getElementById('typography').addEventListener('change', function(e) {
            document.body.style.fontFamily = e.target.value;
        });

        const nationalitySelect = document.getElementById('nationality');
        if (nationalitySelect) {
            fetch('/api/countries').then(res => res.json()).then(data => {
                data.forEach(country => {
                    const option = document.createElement('option');
                    option.value = `${country.name} (${country.code})`;
                    option.textContent = `${country.name} (${country.code})`;
                    nationalitySelect.appendChild(option);
                });
            });
        }

        const phoneCodeMenu = document.getElementById('phone_code_menu');
        if (phoneCodeMenu) {
            fetch('/api/countries').then(res => res.json()).then(data => {
                data.forEach(country => {
                    if (country.phone) {
                        const li = document.createElement('li');
                        const a = document.createElement('a');
                        a.classList.add('dropdown-item');
                        a.textContent = `${country.name} (${country.phone})`;
                        a.addEventListener('click', e => {
                            e.preventDefault();
                            document.getElementById('selected_code').textContent = country.phone;
                            document.getElementById('phoneCode').value = country.phone;
                        });
                        li.appendChild(a);
                        phoneCodeMenu.appendChild(li);
                    }
                });
            });
        }

        // Lógica de Modal Dinâmica
        function showModal(type, title, message, footer = '') {
            const modal = new bootstrap.Modal(document.getElementById('globalModal'));
            const modalHeader = document.querySelector('#globalModal .modal-header');
            const modalBody = document.querySelector('#globalModal .modal-body');
            const modalFooter = document.querySelector('#globalModal .modal-footer');

            modalHeader.className = 'modal-header';
            if (type === 'success') modalHeader.classList.add('bg-success', 'text-white');
            else if (type === 'error') modalHeader.classList.add('bg-danger', 'text-white');
            else if (type === 'delete') modalHeader.classList.add('bg-danger', 'text-white');

            document.getElementById('globalModalLabel').textContent = title;
            modalBody.innerHTML = message;
            modalFooter.innerHTML = footer;

            modal.show();
        }

        // Modais de Sucesso e Erro
        @if(session('msg'))
            showModal('success', 'Sucesso', '{{ session('msg') }}');
        @endif
        @if($errors->any())
            showModal('error', 'Erro(s)', '@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach');
        @endif

        // Modal de Deleção
        document.addEventListener('click', e => {
            const btn = e.target.closest('.delete-btn');
            if (btn) {
                e.preventDefault();
                const url = btn.dataset.url;
                showModal('delete', 'Confirmar Exclusão', 'Tem certeza?', `
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="${url}" class="btn btn-danger">Deletar</a>
                `);
            }
        });
    </script>
    @yield('scripts')
    @stack('scripts')
    
</body>
</html>