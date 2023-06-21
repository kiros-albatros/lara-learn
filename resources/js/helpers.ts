/**
 * Get deep nested Object/Array value by dot-separated path (analog of Laravel's data_get)
 * @param target: Object|Array
 * @param path: Array|string
 * @param defaultValue
 * @return mixed
 */
export function dataGet(target: any, path: string | string[], defaultValue: any = null){
    let segments = Array.isArray(path) ? path : path.split('.');
    let child = target;
    for (const segment of segments){
        if (child[segment] === undefined) return defaultValue;
        child = child[segment];
    }
    return child;
}

/**
 * Set deep nested Object/Array value by dot-separated path (analog of Laravel's data_set)
 * @param target
 * @param path
 * @param value
 */
export function dataSet(target: any, path: string | string[], value: any){
    let segments = Array.isArray(path) ? path : path.split('.');
    let child = target;
    while (segments.length > 1){
        let segment = segments.shift();
        // @ts-ignore
        if (child[segment] === undefined) child[segment] = {};
        // @ts-ignore
        child = child[segment];
    }
    if (value === undefined){
        // Key deletion
        if (Array.isArray(child)){
            child.splice(parseInt(segments[0]), 1);
        }
        else {
            delete child[segments[0]];
        }
    }
    else {
        child[segments[0]] = value;
    }
}

/**
 * Check if string is a valid JSON
 * @param text
 * @return boolean
 */
export function isValidJson(text: string){
    try {
        JSON.parse(text);
    }
    catch (e) {
        return false;
    }
    return true;
}
