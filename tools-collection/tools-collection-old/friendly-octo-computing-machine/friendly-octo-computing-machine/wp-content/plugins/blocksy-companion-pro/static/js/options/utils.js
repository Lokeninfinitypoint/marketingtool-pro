import phpUnserialize from 'phpunserialize'

export const safePhpUnserialize = (data) => {
	const fixed = data.replace(
		/s:(\d+):"((?:\\.|[^"\\])*)";/g,
		(_, len, str) => {
			// If multiline, skip length correction to avoid breaking structure
			if (str.includes('\n')) return `s:${len}:"${str}";`
			const actualLength = Buffer.byteLength(str, 'utf8')
			return `s:${actualLength}:"${str}";`
		}
	)

	return phpUnserialize(fixed)
}
