<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('alumnos.index') }}" class="brand-link">
        <span class="brand-text font-weight-light">Gestión Académica</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-header">GESTIÓN</li>
                <li class="nav-item">
                    <a href="{{ route('alumnos.index') }}" class="nav-link {{ request()->routeIs('alumnos.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>Alumnos</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('docentes.index') }}" class="nav-link {{ request()->routeIs('docentes.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chalkboard-teacher"></i>
                        <p>Docentes</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('cursos.index') }}" class="nav-link {{ request()->routeIs('cursos.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-school"></i>
                        <p>Cursos</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('planes-de-estudio.index') }}" class="nav-link {{ request()->routeIs('planes-de-estudio.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book-open"></i>
                        <p>Planes de Estudio</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>