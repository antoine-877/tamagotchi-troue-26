// Small formatting helpers shared by the pages.

// A frame or file number on three digits: 7 → « 007 ».
export const frameNumber = (id) => String(id).padStart(3, '0');

// A rank or section number on two digits: 4 → « 04 ».
export const twoDigits = (n) => String(n).padStart(2, '0');
