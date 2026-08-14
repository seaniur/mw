"use client";

import { useState, type FormEvent } from "react";
import { motion, AnimatePresence } from "framer-motion";
import { ArrowUpRight, CheckCircle2 } from "lucide-react";

const inputClasses =
  "w-full rounded-xl border border-hairline bg-paper px-4 py-3 text-sm text-ink placeholder:text-muted focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange transition-colors";

const labelClasses =
  "text-[0.68rem] font-semibold tracking-[0.14em] text-muted uppercase";

// Submission is a local placeholder until the WordPress/API integration is wired up.
export function ContactForm() {
  const [submitted, setSubmitted] = useState(false);
  const [submitting, setSubmitting] = useState(false);

  function handleSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setSubmitting(true);
    window.setTimeout(() => {
      setSubmitting(false);
      setSubmitted(true);
    }, 600);
  }

  if (submitted) {
    return (
      <motion.div
        initial={{ opacity: 0, y: 12 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.4 }}
        className="flex flex-col items-center gap-3 rounded-2xl border border-hairline bg-paper-soft p-10 text-center"
      >
        <CheckCircle2 className="text-orange" size={32} />
        <h3 className="font-display text-lg font-bold text-ink">
          Message received
        </h3>
        <p className="max-w-xs text-sm leading-relaxed text-body">
          Thanks for reaching out — our team will follow up within one
          business day.
        </p>
      </motion.div>
    );
  }

  return (
    <AnimatePresence>
      <motion.form
        onSubmit={handleSubmit}
        initial={{ opacity: 0, y: 12 }}
        animate={{ opacity: 1, y: 0 }}
        exit={{ opacity: 0 }}
        transition={{ duration: 0.4 }}
        className="flex flex-col gap-5"
      >
        <div className="grid gap-5 sm:grid-cols-2">
          <div className="flex flex-col gap-2">
            <label htmlFor="name" className={labelClasses}>
              Name
            </label>
            <input
              id="name"
              name="name"
              type="text"
              required
              autoComplete="name"
              className={inputClasses}
              placeholder="Jane Cooper"
            />
          </div>
          <div className="flex flex-col gap-2">
            <label htmlFor="company" className={labelClasses}>
              Company
            </label>
            <input
              id="company"
              name="company"
              type="text"
              autoComplete="organization"
              className={inputClasses}
              placeholder="Your company"
            />
          </div>
        </div>

        <div className="flex flex-col gap-2">
          <label htmlFor="email" className={labelClasses}>
            Email
          </label>
          <input
            id="email"
            name="email"
            type="email"
            required
            autoComplete="email"
            className={inputClasses}
            placeholder="you@company.com"
          />
        </div>

        <div className="flex flex-col gap-2">
          <label htmlFor="interest" className={labelClasses}>
            What are you looking for?
          </label>
          <select id="interest" name="interest" className={inputClasses} defaultValue="">
            <option value="" disabled>
              Select an area
            </option>
            <option value="sourcing">Sourcing & Supplier Network</option>
            <option value="manufacturing">Manufacturing & Private Label</option>
            <option value="rd">R&D & Formulation</option>
            <option value="quality">Quality & Compliance</option>
            <option value="packaging">Packaging & Branding</option>
            <option value="logistics">Logistics & Fulfillment</option>
            <option value="other">Something else</option>
          </select>
        </div>

        <div className="flex flex-col gap-2">
          <label htmlFor="message" className={labelClasses}>
            Message
          </label>
          <textarea
            id="message"
            name="message"
            required
            rows={5}
            className={inputClasses}
            placeholder="Tell us about your product, timeline, and target markets."
          />
        </div>

        <button
          type="submit"
          disabled={submitting}
          className="brand-gradient group mt-2 inline-flex cursor-pointer items-center justify-center gap-2 rounded-full px-6 py-3.5 text-sm font-medium text-paper shadow-[0_8px_24px_-8px_rgba(226,124,57,0.55)] transition-all duration-200 hover:-translate-y-0.5 hover:shadow-[0_10px_28px_-6px_rgba(226,124,57,0.65)] disabled:cursor-not-allowed disabled:opacity-70"
        >
          {submitting ? "Sending..." : "Send Message"}
          {!submitting && (
            <ArrowUpRight
              size={16}
              className="transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5"
            />
          )}
        </button>
      </motion.form>
    </AnimatePresence>
  );
}
