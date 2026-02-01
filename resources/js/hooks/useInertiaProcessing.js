import { useState, useEffect } from 'react';
import { router } from '@inertiajs/react';

export function useInertiaProcessing() {
    const [processing, setProcessing] = useState(false);

    useEffect(() => {
        const handleStart = () => setProcessing(true);
        const handleFinish = () => setProcessing(false);

        const removeStartListener = router.on('start', handleStart);
        const removeFinishListener = router.on('finish', handleFinish);

        return () => {
            removeStartListener();
            removeFinishListener();
        };
    }, []);

    return processing;
}