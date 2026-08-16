<?php
/**
 * Contact form: honeypot anti-spam field, POSTs via fetch() to
 * admin-ajax.php (see assets/js/main.js "contact-form" handler and
 * metwiser_handle_contact_form() in functions.php). Swaps to the success
 * panel in place on a 200 response.
 */
?>
<div id="contact-form-wrap">
    <form id="contact-form" class="flex flex-col gap-5">
        <input type="hidden" name="action" value="metwiser_contact_form">
        <input type="hidden" name="nonce" value="<?php echo esc_attr(wp_create_nonce('metwiser_contact_form')); ?>">

        <!-- Honeypot — hidden from real visitors, bots that autofill every field trip it. -->
        <input type="text" name="hp_field" tabindex="-1" autocomplete="off" aria-hidden="true" class="absolute h-0 w-0 overflow-hidden opacity-0">

        <div class="grid gap-5 sm:grid-cols-2">
            <div class="flex flex-col gap-2">
                <label for="name" class="form-label">Name</label>
                <input id="name" name="name" type="text" required autocomplete="name" class="form-input" placeholder="Jane Cooper">
            </div>
            <div class="flex flex-col gap-2">
                <label for="company" class="form-label">Company</label>
                <input id="company" name="company" type="text" autocomplete="organization" class="form-input" placeholder="Your company">
            </div>
        </div>

        <div class="flex flex-col gap-2">
            <label for="email" class="form-label">Email</label>
            <input id="email" name="email" type="email" required autocomplete="email" class="form-input" placeholder="you@company.com">
        </div>

        <div class="flex flex-col gap-2">
            <label for="interest" class="form-label">What are you looking for?</label>
            <select id="interest" name="interest" class="form-input" defaultvalue="">
                <option value="" disabled selected>Select an area</option>
                <option value="sourcing">Sourcing & Supplier Network</option>
                <option value="manufacturing">Manufacturing & Private Label</option>
                <option value="rd">R&D & Formulation</option>
                <option value="quality">Quality & Compliance</option>
                <option value="packaging">Packaging & Branding</option>
                <option value="logistics">Logistics & Fulfillment</option>
                <option value="other">Something else</option>
            </select>
        </div>

        <div class="flex flex-col gap-2">
            <label for="message" class="form-label">Message</label>
            <textarea id="message" name="message" required rows="5" class="form-input" placeholder="Tell us about your product, timeline, and target markets."></textarea>
        </div>

        <div id="contact-form-error" class="hidden items-start gap-2 text-sm text-terracotta" role="alert">
            <span class="mt-0.5 shrink-0"><?php echo metwiser_icon('alert-circle', 16); ?></span>
            <span id="contact-form-error-text"></span>
        </div>

        <button type="submit" class="btn-primary mt-2 w-full sm:w-auto">
            <span id="contact-form-submit-label">Send Message</span>
            <?php echo metwiser_icon('arrow-up-right', 16, 'transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5'); ?>
        </button>
    </form>

    <div id="contact-form-success" class="hidden flex-col items-center gap-3 rounded-2xl border border-hairline bg-paper-soft p-10 text-center">
        <span class="text-orange"><?php echo metwiser_icon('check-circle-2', 32); ?></span>
        <h3 class="font-display text-lg font-bold text-ink">Message received</h3>
        <p class="max-w-xs text-sm leading-relaxed text-body">
            Thanks for reaching out. Our team will follow up within one business day.
        </p>
    </div>
</div>
