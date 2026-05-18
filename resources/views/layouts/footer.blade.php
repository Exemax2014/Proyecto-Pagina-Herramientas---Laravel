<footer class="footer-main">
    <div class="footer-inner">
        <div class="footer-columns">
            <!-- LOGO -->
            <div class="footer-section footer-logo-block">
                <img
                    src="{{ asset('img/LOGO-HIERRO&FORJA.png') }}"
                    alt="HIERRO &amp; FORJA"
                    class="footer-logo"
                >
            </div>

            <!-- BLOQUE DERECHO -->
            <div class="footer-right-block">
                <!-- POR QUÉ ELEGIRNOS -->
                <div class="footer-section footer-brand-block">
                    <h3 class="footer-title">Por qu&eacute; elegirnos</h3>
                    <p class="footer-description">
                        Acompa&ntilde;amos cada compra con variedad, asesoramiento y productos pensados
                        para trabajo real. Te ayudamos a elegir bien seg&uacute;n tu rubro, necesidad y presupuesto.
                    </p>
                </div>

                <!-- LINKS -->
                <div class="footer-links-row">
                    
                    <div class="footer-section footer-links-col">
                        <h4 class="footer-subtitle">Soporte</h4>
                        <ul class="footer-links">
                            <li><a href="{{ route('contacto') }}">Contacto</a></li>
                            <li><a href="{{ route('terminos') }}">T&eacute;rminos</a></li>
                        </ul>
                    </div>

                    <div class="footer-section footer-links-col">
                        <h4 class="footer-subtitle">Sobre la empresa</h4>
                        <ul class="footer-links">
                            <li><a href="{{ route('quienes-somos') }}">Qui&eacute;nes Somos</a></li>
                            <li><a href="{{ route('comercializacion') }}">Comercializaci&oacute;n</a></li>
                        </ul>
                    </div>
                    
                </div>
            </div>
        </div>

        <hr class="footer-divider">

        <div class="row align-items-center footer-bottom-row">
            <div class="col-md-6 col-12 text-center text-md-start mb-2 mb-md-0">
                <p class="footer-copyright mb-0">
                    &copy; 2026 HIERRO &amp; FORJA. Todos los derechos reservados.
                </p>
            </div>

            <div class="col-md-6 col-12 text-center text-md-end">
                <p class="footer-tagline mb-0">
                    Calidad, precisi&oacute;n y confianza en cada herramienta.
                </p>
            </div>
        </div>
    </div>
</footer>