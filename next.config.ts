import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  // Static export for shared/cPanel-style hosting — no Node.js server required.
  output: "export",
  // Folder + index.html output (about/index.html) matches Apache's default
  // DirectoryIndex behavior with no .htaccess rewrite rules needed.
  trailingSlash: true,
  images: {
    // next/image's optimization API needs a server; static export has none.
    unoptimized: true,
  },
};

export default nextConfig;
