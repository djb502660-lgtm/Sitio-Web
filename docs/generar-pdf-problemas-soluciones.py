#!/usr/bin/env python3
"""Genera el PDF de problemas y soluciones del proyecto Sitio-Web / CAFEESQUINA."""

from pathlib import Path

from fpdf import FPDF

OUTPUT = Path(__file__).resolve().parent / "PROBLEMAS-Y-SOLUCIONES-PROYECTO.pdf"

# Helvetica core font: solo Latin-1 seguro
_TRANSLATE = str.maketrans({
    "á": "a", "é": "e", "í": "i", "ó": "o", "ú": "u",
    "Á": "A", "É": "E", "Í": "I", "Ó": "O", "Ú": "U",
    "ñ": "n", "Ñ": "N", "ü": "u", "Ü": "U",
    "—": "-", "–": "-", "«": '"', "»": '"',
    "→": "->", "•": "-",
})


def latin1(text: str) -> str:
    return text.translate(_TRANSLATE)


class DocPDF(FPDF):
    def footer(self) -> None:
        self.set_y(-15)
        self.set_font("Helvetica", "I", 8)
        self.set_text_color(120, 120, 120)
        self.cell(0, 10, f"Sitio-Web / CAFEESQUINA - Pagina {self.page_no()}", align="C")

    def section_title(self, text: str) -> None:
        self.ln(4)
        self.set_font("Helvetica", "B", 14)
        self.set_text_color(93, 64, 55)
        self.multi_cell(0, 8, latin1(text))
        self.ln(2)

    def subsection_title(self, text: str) -> None:
        self.ln(2)
        self.set_font("Helvetica", "B", 11)
        self.set_text_color(141, 110, 99)
        self.multi_cell(0, 7, latin1(text))
        self.ln(1)

    def body(self, text: str) -> None:
        self.set_font("Helvetica", "", 10)
        self.set_text_color(30, 30, 30)
        self.multi_cell(0, 5.5, latin1(text))
        self.ln(2)

    def table_header(self, cols: list[tuple[str, float]]) -> None:
        self.set_font("Helvetica", "B", 9)
        self.set_fill_color(93, 64, 55)
        self.set_text_color(255, 255, 255)
        for label, width in cols:
            self.cell(width, 8, latin1(label), border=1, fill=True)
        self.ln()

    def table_row(self, cols: list[tuple[str, float]], fill: bool = False) -> None:
        self.set_font("Helvetica", "", 8.5)
        self.set_text_color(30, 30, 30)
        if fill:
            self.set_fill_color(245, 245, 245)
        x0, y0 = self.get_x(), self.get_y()
        heights = []
        texts = []
        for text, width in cols:
            text = latin1(text)
            texts.append(text)
            nb = self.get_string_width(text) / (width - 2) + 1
            heights.append(max(8, nb * 4.5))
        row_h = max(heights)
        if self.get_y() + row_h > 280:
            self.add_page()
            y0 = self.get_y()
        for text, width in zip(texts, [w for _, w in cols]):
            x = self.get_x()
            self.set_xy(x, y0)
            self.multi_cell(width, 4.5, text, border=1, fill=fill)
            self.set_xy(x + width, y0)
        self.set_xy(x0, y0 + row_h)


def build_pdf() -> FPDF:
    pdf = DocPDF(orientation="P", unit="mm", format="A4")
    pdf.set_auto_page_break(auto=True, margin=20)
    pdf.set_margins(18, 18, 18)
    pdf.add_page()

    pdf.set_font("Helvetica", "B", 20)
    pdf.set_text_color(62, 39, 35)
    pdf.cell(0, 12, "Sitio-Web / CAFEESQUINA", new_x="LMARGIN", new_y="NEXT")
    pdf.set_font("Helvetica", "", 10)
    pdf.set_text_color(90, 90, 90)
    pdf.multi_cell(
        0,
        5,
        "Documento técnico: Problemas encontrados y soluciones aplicadas\n"
        "Fecha: 6 de junio de 2026  |  Enfoque: spec-as-source",
    )
    pdf.ln(4)

    pdf.section_title("1. Resumen ejecutivo")
    pdf.body(
        "El repositorio Sitio-Web evolucionó en tres fases: instalación Laravel 12, aplicación MVC "
        "independiente (Catálogo de Productos con Compra por WhatsApp) y reemplazo total por "
        "CAFEESQUINA, cafetería digital con menú, promociones, pedidos por WhatsApp y panel "
        "administrativo. La arquitectura final unifica todas las capas del repositorio bajo una "
        "sola entrada web: http://localhost/Sitio-Web/."
    )

    pdf.section_title("2. Mapa de capas y roles del repositorio")
    pdf.body(
        "Cada carpeta tiene un rol definido en AGENTS.md, README.md y docs/ESTRUCTURA-PROYECTO.md:"
    )
    pdf.table_header([("Capa", 42), ("Ruta", 38), ("Rol", 92)])
    layers = [
        ("Orquestacion - planes", "spec/", "Que construir (fuente de verdad por solicitud)"),
        ("Orquestacion - ejecucion IA", "skill/", "Como debe trabajar el agente de IA"),
        ("Aplicacion activa", "cafeesquina/", "Motor MVC (PHP 8 + PDO): negocio CAFEESQUINA"),
        (
            "Framework (envoltorio)",
            "app/, routes/, public/, database/, tests/",
            "Laravel 12: Bridge, migraciones, pruebas, assets Vite",
        ),
        ("Documentacion", "docs/", "Arquitectura, despliegue y guias"),
    ]
    for i, row in enumerate(layers):
        pdf.table_row(list(zip(row, [42, 38, 92])), fill=i % 2 == 0)
    pdf.ln(2)
    pdf.body(
        "Regla de oro: no escribir código en la capa aplicación sin plan (spec/NNN-*.md) "
        "y skill (skill/<slug>/SKILL.md)."
    )

    pdf.section_title("3. Roles de usuario en CAFEESQUINA")
    pdf.table_header([("Rol", 35), ("Nombre en BD", 35), ("Permisos", 102)])
    users = [
        ("Visitante", "-", "Ver landing, menu, promociones; pedir por WhatsApp"),
        ("Cliente", "client", "Registro, login, perfil, historial de pedidos"),
        ("Administrador", "admin", "Dashboard, CRUD productos/categorias/promociones/usuarios"),
    ]
    for i, row in enumerate(users):
        pdf.table_row(list(zip(row, [35, 35, 102])), fill=i % 2 == 0)
    pdf.body(
        "Credenciales por defecto del administrador: admin@cafeesquina.local / Admin123! "
        "(cambiar en producción)."
    )

    pdf.section_title("4. Skills y planes asociados")
    pdf.table_header([("Skill", 40), ("Plan spec", 58), ("Función", 74)])
    skills = [
        ("spec-as-source", "spec/000-orquestacion-spec-as-source.md", "Orquestación: planificar antes de codificar"),
        ("testing-layer", "Transversal", "Pruebas obligatorias al cerrar cada etapa"),
        ("security-layer", "Transversal", "Checklist S1-S12 de seguridad"),
        ("catalogo-whatsapp", "spec/001-catalogo-whatsapp.md", "Primera app MVC (reemplazada)"),
        ("cafeesquina", "spec/002-cafeesquina.md", "App actual de cafetería"),
    ]
    for i, row in enumerate(skills):
        pdf.table_row(list(zip(row, [40, 58, 74])), fill=i % 2 == 0)

    pdf.section_title("5. Problemas y soluciones (detalle)")

    problems: list[tuple[str, list[tuple[str, str]]]] = [
        (
            "5.1 Arranque inicial del proyecto",
            [
                (
                    "P1 — Dependencias PHP ausentes. php artisan serve fallaba sin vendor/.",
                    "Ejecutar composer install. Crear .env desde .env.example y php artisan key:generate.",
                ),
                (
                    "P2 — Base de datos sin esquema.",
                    "php artisan migrate --seed con DB_DATABASE configurado (sitio_web o cafeesquina).",
                ),
                (
                    "P3 — composer run dev falla en Windows. Laravel Pail requiere pcntl (Linux/macOS).",
                    "Levantar php artisan serve y npm run dev por separado. Documentado en spec/000.",
                ),
            ],
        ),
        (
            "5.2 Confusión arquitectónica entre capas",
            [
                (
                    "P4 — Doble significado de «aplicación». spec-as-source vs app/ de Laravel.",
                    "Documentar mapa en AGENTS.md y ESTRUCTURA-PROYECTO.md. Negocio en cafeesquina/.",
                ),
                (
                    "P5 — Laravel dormido. routes/, database/, tests/ sin uso tras crear CAFEESQUINA.",
                    "Unificación: Laravel entrada única; CafeesquinaBridgeController; migraciones y tests Feature.",
                ),
                (
                    "P6 — Código residual product_catalog/. Dos apps MVC paralelas.",
                    "Eliminar product_catalog/ según spec/002. Conservar spec/001 como registro histórico.",
                ),
                (
                    "P7 — Agente salta planificación. Código ad hoc sin spec.",
                    "Skill spec-as-source: Fase A (plan+skill) antes de Fase B (código por etapas).",
                ),
            ],
        ),
        (
            "5.3 Enrutamiento, URLs y sesiones (XAMPP)",
            [
                (
                    "P8 — Base path variable. Enlaces y cookies rotos en /Sitio-Web/.",
                    "base_url() y ce_app_base_path(). APP_URL=http://localhost/Sitio-Web en .env.",
                ),
                (
                    "P9 — Sesión admin no persistía tras login.",
                    "Ajustar cookie path de sesión PHP al subdirectorio del proyecto.",
                ),
                (
                    "P10 — Rutas duplicadas /cafeesquina/.",
                    "Redirecciones 301 en routes/web.php hacia la raíz unificada.",
                ),
                (
                    "P11 — Conflicto CSRF Laravel vs cafeesquina.",
                    "Bridge sin VerifyCsrfToken de Laravel; tokens CSRF propios del MVC.",
                ),
                (
                    "P12 — dirname() en Windows devuelve \\ en rutas.",
                    "Normalización explícita en cafeesquina/config/helpers.php.",
                ),
            ],
        ),
        (
            "5.4 Base de datos y migraciones",
            [
                (
                    "P13 — Dos bases: product_catalog y cafeesquina.",
                    "Esquema unificado: users, categories, products, promotions, orders. Roles client/admin.",
                ),
                (
                    "P14 — Migración manual repetible.",
                    "migrate.sql + scripts/migrate.php; preferir php artisan migrate --seed.",
                ),
                (
                    "P15 — Credenciales admin inconsistentes.",
                    "Alinear hash bcrypt y docs con admin@cafeesquina.local / Admin123!.",
                ),
            ],
        ),
        (
            "5.5 Integración WhatsApp y negocio",
            [
                (
                    "P16 — Número WhatsApp con espacios (593 96 394 7808) rompía wa.me.",
                    "Sanitizar dígitos con preg_replace. Número: 593963947808 en config/cafeesquina.php.",
                ),
                (
                    "P17 — Mensaje de pedido incompleto.",
                    "Plantilla dinámica con nombre y precio del producto.",
                ),
            ],
        ),
        (
            "5.6 Archivos estáticos, imágenes y frontend",
            [
                (
                    "P18 — Imágenes rotas en admin.",
                    "Refactor upload_url() para subcarpetas products/ y promotions/.",
                ),
                (
                    "P19 — CSS/JS con MIME incorrecto.",
                    "Rutas en web.php con mapa MIME explícito para .css, .js, .svg.",
                ),
                (
                    "P20 — Diseño frontend insatisfactorio en primera iteración.",
                    "Rediseño: paleta café, glassmorphism, cafeesquina.css, Tailwind 3 responsive.",
                ),
                (
                    "P21 — Assets Vite sin compilar en producción.",
                    "npm run build antes de desplegar; npm run dev en desarrollo.",
                ),
            ],
        ),
        (
            "5.7 Seguridad",
            [
                ("P22 — XSS en formularios.", "Helper e() con htmlspecialchars en todas las salidas."),
                ("P23 — SQL injection.", "PDO prepared statements en cafeesquina/models/."),
                ("P24 — Acceso admin sin rol.", "require_admin() y rol admin en rutas administrativas."),
                ("P25 — Uploads inseguros.", "Validación MIME/tamaño; almacenamiento en cafeesquina/uploads/."),
                ("P26 — Contraseñas inseguras.", "password_hash/verify; regeneración de sesión al login."),
            ],
        ),
        (
            "5.8 Testing y calidad",
            [
                (
                    "P27 — Sin pruebas automatizadas en MVC puro.",
                    "CafeesquinaHomeTest, CafeesquinaAssetTest; php artisan test --filter=Cafeesquina.",
                ),
                (
                    "P28 — Regresiones tras unificación.",
                    "Auditoría Bridge, rutas y SCRIPT_NAME en tests.",
                ),
            ],
        ),
        (
            "5.9 Despliegue y entornos",
            [
                (
                    "P29 — Netlify no ejecuta PHP.",
                    "DESPLIEGUE.md: Netlify solo para assets Vite; producción requiere hosting PHP.",
                ),
                (
                    "P30 — 404 en todas las rutas.",
                    "Document root = public/; mod_rewrite y .htaccess activos.",
                ),
                (
                    "P31 — Error 500 en producción.",
                    "Permisos storage/; APP_DEBUG=false; revisar storage/logs/laravel.log.",
                ),
            ],
        ),
    ]

    for section_title, items in problems:
        pdf.subsection_title(section_title)
        pdf.table_header([("Problema", 87), ("Solución", 85)])
        for i, (prob, sol) in enumerate(items):
            pdf.table_row([(prob, 87), (sol, 85)], fill=i % 2 == 0)
        pdf.ln(2)

    pdf.section_title("6. Flujo de petición (arquitectura final)")
    for step in [
        "1. Apache recibe petición en http://localhost/Sitio-Web/.",
        "2. index.php (raíz) → public/index.php (Laravel).",
        "3. routes/web.php sirve /assets/* y /uploads/* desde cafeesquina/.",
        "4. Otras rutas → CafeesquinaBridgeController → cafeesquina/router.php → controladores MVC.",
    ]:
        pdf.body(step)

    pdf.section_title("7. Comandos de referencia")
    pdf.table_header([("Acción", 55), ("Comando", 117)])
    cmds = [
        ("Instalar dependencias", "composer install"),
        ("Migrar y sembrar BD", "php artisan migrate --seed"),
        ("Pruebas CAFEESQUINA", "php artisan test --filter=Cafeesquina"),
        ("Desarrollo (Windows)", "php artisan serve + npm run dev"),
        ("Assets producción", "npm run build"),
        ("URL local XAMPP", "http://localhost/Sitio-Web/"),
    ]
    for i, row in enumerate(cmds):
        pdf.table_row(list(zip(row, [55, 117])), fill=i % 2 == 0)

    pdf.section_title("8. Solicitudes del proyecto (historial spec)")
    pdf.table_header([("ID", 15), ("Nombre", 85), ("Estado", 72)])
    specs = [
        ("000", "Orquestación spec-as-source", "Etapa 1 completada; validación continua en curso"),
        ("001", "Catálogo de Productos con Compra por WhatsApp", "Reemplazado por 002; referencia histórica"),
        ("002", "CAFEESQUINA - Cafeteria digital", "App activa: base, publico, auth, admin, assets"),
    ]
    for i, row in enumerate(specs):
        pdf.table_row(list(zip(row, [15, 85, 72])), fill=i % 2 == 0)

    pdf.section_title("9. Conclusiones")
    pdf.body(
        "El mayor desafío fue la convivencia de dos paradigmas (Laravel + MVC PHP puro) bajo spec-as-source. "
        "La solución definitiva: roles claros por carpeta, eliminar código obsoleto, unificar la entrada mediante "
        "CafeesquinaBridgeController y documentar en spec/, skill/ y docs/."
    )
    pdf.body(
        "Para continuar: crear spec/003-<feature>.md, su skill, implementar por etapas en cafeesquina/, "
        "y no avanzar sin testing-layer y security-layer."
    )
    pdf.set_font("Helvetica", "I", 8)
    pdf.set_text_color(120, 120, 120)
    pdf.multi_cell(
        0,
        4,
        "Referencias: AGENTS.md · docs/ESTRUCTURA-PROYECTO.md · docs/DESPLIEGUE.md · "
        "cafeesquina/INSTALL.md · spec/000, 001, 002",
    )

    return pdf


def main() -> None:
    pdf = build_pdf()
    pdf.output(str(OUTPUT))
    print(f"PDF generado: {OUTPUT}")


if __name__ == "__main__":
    main()
