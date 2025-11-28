export default async () => {
  const tailwindPostcss = (await import('@tailwindcss/postcss')).default;
  const autoprefixer = (await import('autoprefixer')).default;
  return {
    plugins: [
      tailwindPostcss(),
      autoprefixer(),
    ],
  };
};
