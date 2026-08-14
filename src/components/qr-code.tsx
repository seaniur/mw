import QRCode from "qrcode";

export async function QrCode({
  value,
  className,
}: {
  value: string;
  className?: string;
}) {
  const svg = await QRCode.toString(value, {
    type: "svg",
    margin: 0,
    color: { dark: "#211c18", light: "#00000000" },
  });

  // Server-generated SVG from a trusted local encoder, not user input.
  return <div className={className} dangerouslySetInnerHTML={{ __html: svg }} />;
}
