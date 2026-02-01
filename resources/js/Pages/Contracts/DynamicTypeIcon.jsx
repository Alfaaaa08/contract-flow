import React, { lazy, Suspense } from 'react';
import dynamicIconImports from 'lucide-react/dynamicIconImports';

const DynamicIcon = ({ name, ...props }) => {
  const LucideIcon = lazy(dynamicIconImports[name]);

  return (
    <Suspense fallback={<div className="h-4 w-4 animate-pulse bg-muted rounded" />}>
      <LucideIcon {...props} />
    </Suspense>
  );
};

export default DynamicIcon;