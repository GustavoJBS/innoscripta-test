import Image from "next/image";

type CardProps = {
    title: string;
    navigateUrl: string;
    description?: string;
    imageUrl?: string;
    source: {
        name: string;
        url: string;
    }
};

const Article: React.FC<CardProps> = ({ title, description, imageUrl, navigateUrl }) => {
    return (
        <a className="flex flex-col gap-3 border-2 p-4 border-gray-300 rounded-lg shadow cursor-pointer hover:opacity-60 duration-300"  href={navigateUrl}>
            <img
                src={imageUrl ?? '/news.png'}
                alt="Image"
                className="mx-auto my-3 w-3/4 max-h-28 object-contain"
                onError={(e) => e.currentTarget.src = "/news.png"}
            />

            <span>
                {title}
            </span>

            {description && <p dangerouslySetInnerHTML={{ __html: description }}></p>}
        </a>
    );
};

export default Article;