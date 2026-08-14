"use client";

import { ThemeProvider as NextThemesProvider } from "next-themes";
import type { ReactNode } from "react";

// Metwiser is light-only per the brand style guide; this exists solely so
// components that call next-themes' useTheme() (e.g. the world map) resolve
// a theme, not to offer a visible dark-mode toggle.
export function ThemeProvider({ children }: { children: ReactNode }) {
  return (
    <NextThemesProvider attribute="class" forcedTheme="light">
      {children}
    </NextThemesProvider>
  );
}
