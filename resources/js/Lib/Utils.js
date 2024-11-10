import { clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs) {
	return twMerge(clsx(inputs));
}

export const plural = (count, noun, suffix = 's') => `${count} ${noun}${count !== 1 ? suffix : ''}`;
export const needsBreakAll = (str, len) => {
	return str.split(' ').some((w) => {
		return w.length >= len;
	});
};
